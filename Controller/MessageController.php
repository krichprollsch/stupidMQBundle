<?php

namespace CoG\StupidMQBundle\Controller;

use CoG\StupidMQ\Exception\NotFoundException;
use CoG\StupidMQ\Message\MessageInterface;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MessageController extends ContainerAware
{
    public function cgetAction(Request $request, $queue)
    {
        if (is_null($request->get('ids'))) {
            throw new \InvalidArgumentException('Request needs ids parameter');
        }

        try {
            $informer = $this->container->get('cog_stupidmq.informer');
            $messages = $informer->getMessages($queue, $request->get('ids'));
            $jsonContent = $this->getSerializer()->serialize($messages, 'json');

            return new Response($jsonContent);
        } catch (\InvalidArgumentException $ex) {
            throw new NotFoundHttpException($ex->getMessage(), $ex);
        }
    }

    public function displayAction(Request $request, $queue)
    {
        try {
            $informer = $this->container->get('cog_stupidmq.informer');
            $first = $request->query->get('first');
            $first = (int)$first == $first && (int)$first > 0 ? $request->query->get('first') : 0;
            $limit = $request->query->get('limit') ? : 10;
            $messages = $informer->getMessagesByInterval($queue, $first, $limit);

            $queueNames = array_keys($informer->getQueues());

            return $this->container->get('templating')->renderResponse(
                'CoGStupidMQBundle:Message:display.html.twig',
                array(
                    'messages' => $messages,
                    'queueNames' => $queueNames,
                    'queue' => $queue,
                    'first' => $first,
                    'limit' => $limit,
                )
            );
        } catch (\InvalidArgumentException $ex) {
            throw new NotFoundHttpException($ex->getMessage(), $ex);
        }
    }

    public function duplicateAction(Request $request, $queue, $id)
    {
        try {
            $informer = $this->container->get('cog_stupidmq.informer');
            $message = $informer->get($queue, $id);
            return new JsonResponse(array('id' => $informer->publish($queue, $message->getContent())->getId()));
        } catch (NotFoundException $ex) {
            throw new NotFoundHttpException($ex->getMessage(), $ex);
        }
    }

    protected function getSerializer()
    {
        return SerializerBuilder::create()
            ->build();
    }
}
