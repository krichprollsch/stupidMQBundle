<?php


namespace CoG\StupidMQBundle\QueueAware;


use CoG\StupidMQ\Queue\QueueInterface;

interface QueueAwareInterface
{
    /**
     * @param QueueInterface $queue
     * @return QueueInterface
     */
    public function addQueue(QueueInterface $queue);
}
