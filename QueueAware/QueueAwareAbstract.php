<?php


namespace CoG\StupidMQBundle\QueueAware;


use CoG\StupidMQ\Queue\QueueInterface;

class QueueAwareAbstract implements QueueAwareInterface
{
    protected $queues = array();

    /**
     * @inheritdoc
     */
    public function addQueue(QueueInterface $queue)
    {
        $this->queues[$queue->getName()] = $queue;
    }

    /**
     * @param string $queue_name
     * @return QueueInterface
     * @throws \InvalidArgumentException
     */
    protected function getQueue($queue_name)
    {
        if (!isset($this->queues[$queue_name])) {
            throw new \InvalidArgumentException(sprintf('Queue %s does not exists', $queue_name));
        }
        return $this->queues[$queue_name];
    }
}
