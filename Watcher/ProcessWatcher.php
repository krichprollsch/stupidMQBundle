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

    public function __construct( $cwd, $command ) {
        $this->queues = array();
        $this->command = $command;
        $this->cwd = $this->cwd;
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
                break;
            }

            if( $wait ) {
                sleep($opt['sleep']);
            }
        } while( true );
    }

    protected function consume( QueueInterface $queue, array $opt ) {
        try {
            $message = $queue->consume();
            $this->log(sprintf('Run message %s found in queue %s', $message->getId(), $queue->getName()), 'info');
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
            $process->run(function ($type, $buffer) use ($logger) {
                if ('err' === $type) {
                    $logger->log($buffer, 'err');
                } else {
                    $logger->log($buffer);
                }
            });

            return true;
        } catch ( RuntimeException $ex ) {
            $this->log(sprintf('Timeout in queue %s', $queue->getName()), 'err');
            return true;
        } catch ( NoResultException $ex ) {
            $this->log(sprintf('No message found in queue %s', $queue->getName()), 'debug');
            return false;
        }
    }

}
