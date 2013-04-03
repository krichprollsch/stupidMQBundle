<?php

namespace CoG\StupidMQBundle\Controller;

use JMS\Serializer\SerializerBuilder;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MessageController extends ContainerAware
{
    public function cgetAction(Request $request, $queue)
    {
        try {
            $informer = $this->container->get('cog_stupidmq.informer');

            $messages = $informer->getMessages($queue, $request->get('ids'));
            $jsonContent = $this->getSerializer()->serialize($messages, 'json');

            return new Response($jsonContent);
        } catch (\InvalidArgumentException $ex) {
            throw new NotFoundHttpException($ex->getMessage(), $ex);
        }
    }

    protected function getSerializer()
    {
        return SerializerBuilder::create()
            ->build();
    }
}
