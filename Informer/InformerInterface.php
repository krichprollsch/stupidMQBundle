<?php

namespace CoG\StupidMQBundle\Informer;

use CoG\StupidMQ\Message\MessageInterface;
use CoG\StupidMQBundle\QueueAware\QueueAwareInterface;

interface InformerInterface extends QueueAwareInterface
{
    /**
     * @param $queue_name
     * @param array $ids
     * @return mixed
     */
    public function getMessages($queue_name, array $ids);

    /**
     * @param $queue_name
     * @return mixed
     */
    public function getMessagesByInterval($queue_name, $first, $limit);

    /**
     * @return mixed
     */
    public function getQueues();

    /**
     * @param $queue_name
     * @param string $content
     * @return MessageInterface
     */
    public function publish($queue_name, $content);

    /**
     * @param $queue_name
     * @param $id int id
     * @return MessageInterface
     * @throw NotFoundException
     */
    public function get($queue_name, $id);
}
