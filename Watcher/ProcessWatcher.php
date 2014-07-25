<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *  30/11/12 10:40
 */

namespace CoG\StupidMQBundle\Watcher;

use CoG\StupidMQ\Queue\QueueInterface;
use CoG\StupidMQ\Exception\NoResultException;
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\Process;
use CoG\StupidMQ\Message\MessageInterface;

/**
 * LoopWatcher
 *
 * @author pierre
 */
class ProcessWatcher extends AbstractWatcher
{
    protected $queues;
    protected $cwd;
    protected $command;
    protected $processes;

    public function __construct( $cwd, $command ) {
        $this->queues = array();
        $this->command = $command;
        $this->cwd = $this->cwd;
        $this->processes = array();
    }

    public function addQueue( QueueInterface $queue ) {
        $this->queues[$queue->getName()] = $queue;
    }

    public function watch(array $opt = array()) {
        $opt = $this->getOptions($opt);

        $opt['lifetime-start'] = time();
        $opt['message-treated'] = 0;

        do {
            $wait = true;
            foreach( $this->queues as $queue ) {
                if( $this->consume( $queue, $opt ) ) {
                    $wait = false;
                    $opt['message-treated']++;
                }
            }

            if($this->shouldStop($opt)) {
                $this->processLoop($wait = true);
                break;
            }

            if( $wait ) {
                sleep($opt['sleep']);
            }

            $this->processLoop();

        } while( true );
    }

    protected function consume( QueueInterface $queue, array $opt ) {
        try {
            while ($opt['max-process'] > 0 &&
                count($this->processes) >= $opt['max-process']
            ) {
                $this->processLoop();
                usleep(1000);
            }

            $message = $queue->consume();
            $process = new Process(
                sprintf(
                    $this->command,
                    $queue->getName(),
                    $message->getId()
                )
            );

            $process->setTimeout(
                $opt['process-timeout']
            );

            $logger = $this;
            $process->start();

            $this->log(
                sprintf(
                    'Run message %s found in queue %s',
                    $message->getId(),
                    $queue->getName()
                ),
                'info'
            );

            $this->processes[] = array(
                'process' => $process,
                'message' => $message,
                'queue' => $queue
            );

            return true;
        } catch ( NoResultException $ex ) {
            $this->log(sprintf('No message found in queue %s', $queue->getName()), 'debug');
            return false;
        }
    }

    protected function processLoop($wait = false)
    {
        foreach ($this->processes as $key => $data) {
            $process = $data['process'];
            $message = $data['message'];
            $queue = $data['queue'];
            try {
                if (!$process->isRunning()) {
                    $stdout = $process->getOutput();
                    if (!empty($stdout)) {
                        $this->log($stdout, 'info');
                    }
                    $stderr = $process->getErrorOutput();
                    if (!empty($stderr)) {
                        $this->log($stderr, 'err');
                    }
                    unset($this->processes[$key]);
                    $this->log(sprintf('end message %s', $message->getId()), 'debug');
                } elseif ($wait) {
                    $logger = $this;
                    $process->wait(function ($type, $buffer) use ($logger) {
                        if ('err' === $type) {
                            $logger->log($buffer, 'err');
                        } else {
                             $logger->log($buffer, 'info');
                        }
                    });
                    unset($this->processes[$key]);
                    $this->log(sprintf('end message %s', $message->getId()), 'debug');
                } else {
                    $process->checkTimeout();
                }
            } catch ( RuntimeException $ex ) {
                unset($this->processes[$key]);
                $queue->feedback($message->getId(), MessageInterface::STATE_ERROR, $ex->getMessage());
                $this->log(sprintf('Exception %s for message %s', $ex->getMessage(), $message->getId()), 'err');
            }
        }

        return count($this->processes);
    }
}
