<?php

namespace CoG\StupidMQBundle\Informer;

use CoG\StupidMQ\Message\MessageInterface;
use CoG\StupidMQBundle\QueueAware\QueueAwareAbstract;

class Informer extends QueueAwareAbstract implements InformerInterface
{
    public function __construct()
    {
        $this->queues = array();
    }

    /**
     * @inheritdoc
     */
    public function getMessages($queue_name, array $ids)
    {
        $queue = $this->getQueue($queue_name);
        return $queue->findAll($ids);
    }

    /**
     * @inheritdoc
     */
    public function getMessagesByInterval($queue_name, $first, $limit)
    {
        $queue = $this->getQueue($queue_name);
        return $queue->findByInterval($first, $limit);
    }

    /**
     * @return mixed
     */
    public function getQueues()
    {
        return $this->queues;
    }

    /**
     * @inheritdoc
     */
    public function publish($queue_name, $content)
    {
        $queue = $this->getQueue($queue_name);
        return $queue->publish($content);
    }

    /**
     * @inheritdoc
     */
    public function get($queue_name, $id)
    {
        $queue = $this->getQueue($queue_name);
        return $queue->get($id);
    }
}