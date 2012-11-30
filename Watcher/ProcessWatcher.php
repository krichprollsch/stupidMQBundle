<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *  30/11/12 10:40
 */

namespace CoG\StupidMQBundle\Watcher;

use CoG\StupidMQ\Queue\QueueInterface;
use CoG\StupidMQ\Exception\NoResultException;
use Symfony\Component\Process\Process;
use CoG\StupidMQBundle\Logger\AbstractLogger;

/**
 * LoopWatcher
 *
 * @author pierre
 */
class ProcessWatcher extends AbstractLogger implements WatcherInterface
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

    public function watch() {
        do {
            $wait = true;
            foreach( $this->queues as $queue ) {
                if( $this->consume( $queue ) ) {
                    $wait = false;
                }
            }

            if( $wait ) {
                sleep(1);
            }
        } while( true );
    }

    protected function consume( QueueInterface $queue ) {
        try {
            $message = $queue->consume();
            $process = new Process(
                sprintf(
                    $this->command,
                    $queue->getName(),
                    $message->getId()
                )
            );

            $process->run(function ($type, $buffer) {
                if ('err' === $type) {
                    $this->log($buffer, 'err');
                } else {
                    $this->log($buffer);
                }
            });

            return true;
        } catch ( NoResultException $ex ) {
            $this->log(sprintf('No message found in queue %s', $queue->getName()), 'debug');
            return false;
        }
    }

}
