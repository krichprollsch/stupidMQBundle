<?php

namespace CoG\StupidMQBundle\Informer;

use CoG\StupidMQBundle\QueueAware\QueueAwareInterface;

interface InformerInterface extends QueueAwareInterface
{
    /**
     * @param $queue_name
     * @param array $ids
     * @return mixed
     */
    public function getMessages($queue_name, array $ids);
}
