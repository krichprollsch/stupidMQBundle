<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *  30/11/12 09:36
 */

namespace CoG\StupidMQBundle\Runner;

use CoG\StupidMQ\Queue\QueueInterface;
use CoG\StupidMQ\Message\MessageInterface;
use CoG\StupidMQBundle\Worker\WorkerInterface;
use CoG\StupidMQ\Exception\NotFoundException;
use CoG\StupidMQBundle\Logger\AbstractLogger;

/**
 * ProcessRunner
 *
 * @author pierre
 */
class SimpleRunner extends AbstractLogger implements RunnerInterface
{
    protected $workers;
    protected $queues;

    public function __construct() {
        $this->workers = array();
        $this->queues = array();
    }

    public function addWorker( WorkerInterface $worker ) {
        $this->workers[$worker->getName()] = $worker;
    }

    public function addQueue( QueueInterface $queue ) {
        $this->queues[$queue->getName()] = $queue;
    }

    public function run($queue_name, $message_id) {
        $this->log(sprintf('Running message id %s in queue %s', $message_id, $queue_name));

        $queue = $this->getQueue($queue_name);
        $workers = $this->getWorkers($queue_name);

        if( count($workers) > 0) {
            $message = $queue->get($message_id);
            foreach( $workers as $worker ) {
                $this->log(sprintf('worker processing message id %s in queue %s', $message_id, $queue_name));
                /* @var $worker WorkerInterface */
                $worker->execute( $message->getContent() );
            }
        } else {
            $this->log(sprintf('No worker registred for queue %s', $queue_name), 'warn');
        }
    }

    /**
     * @param string $queue_name
     * @return QueueInterface
     * @throws \InvalidArgumentException
     */
    protected function getQueue($queue_name) {
        if(!isset($this->queues[$queue_name])) {
            throw new \InvalidArgumentException( sprtinf( 'Queue %s does not exists', $queue_name ));
        }
        return $this->queues[$queue_name];
    }

    protected function getWorkers( $queue_name ) {
        $workers = array();
        foreach( $this->workers as $worker ) {
            /* @var $worker WorkerInterface */
            if( in_array($queue_name, $worker->getSubscribedQueues()) ) {
                $workers[] = $worker;
            }
        }
        return $workers;
    }
}
