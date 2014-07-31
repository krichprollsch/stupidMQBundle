<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *  30/11/12 09:36
 */

namespace CoG\StupidMQBundle\Runner;

use CoG\StupidMQ\Queue\QueueInterface;
use CoG\StupidMQ\Message\MessageInterface;
use CoG\StupidMQBundle\Event\StupidMQErrorEvent;
use CoG\StupidMQBundle\Event\StupidMQEvents;
use CoG\StupidMQBundle\Feeback\Feedback;
use CoG\StupidMQBundle\Worker\WorkerInterface;
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
    protected $eventDispatcher;

    public function __construct($eventDispatcher)
    {
        $this->workers = array();
        $this->queues = array();
        $this->eventDispatcher = $eventDispatcher;
    }

    public function addWorker(WorkerInterface $worker)
    {
        $this->workers[$worker->getName()] = $worker;
    }

    public function addQueue(QueueInterface $queue)
    {
        $this->queues[$queue->getName()] = $queue;
    }

    public function run($queue_name, $message_id)
    {
        $this->log(sprintf('Running message id %s in queue %s', $message_id, $queue_name));

        $queue = $this->getQueue($queue_name);
        $workers = $this->getWorkers($queue_name);

        if (count($workers) > 0) {
            $message = $queue->get($message_id);
            foreach ($workers as $worker) {
                $this->log(
                    sprintf(
                        'Worker %s start processing message id %s in queue %s',
                        $worker->getName(),
                        $message_id,
                        $queue_name
                    )
                );

                try {
                    /* @var $worker WorkerInterface */
                    $feedback = Feedback::createFromValue(
                        $worker->execute($message->getContent())
                    );
                    $queue->feedback($message_id, $feedback->getState(), $feedback->getMessage());

                } catch (\Exception $ex) {
                    $messageFeedback = $queue->feedback($message_id, MessageInterface::STATE_ERROR, $ex->getMessage());
                    $this->eventDispatcher->dispatch(StupidMQEvents::STUPIDMQ_ERROR, new StupidMQErrorEvent($messageFeedback));
                }

                restore_error_handler();

                $this->log(
                    sprintf(
                        'Worker %s end processing message id %s in queue %s',
                        $worker->getName(),
                        $message_id,
                        $queue_name
                    )
                );
            }
        } else {
            $this->log(sprintf('No worker registred for queue %s', $queue_name), 'warn');
            $queue->feedback($message_id, MessageInterface::STATE_CANCELED, 'No worker found');
        }
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

    protected function getWorkers($queue_name)
    {
        $workers = array();
        foreach ($this->workers as $worker) {
            /* @var $worker WorkerInterface */
            if (in_array($queue_name, $worker->getSubscribedQueues())) {
                $workers[] = $worker;
            }
        }
        return $workers;
    }
}
