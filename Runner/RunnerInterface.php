<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *  30/11/12 09:37
 */

namespace CoG\StupidMQBundle\Runner;

use CoG\StupidMQBundle\Worker\WorkerInterface;
use CoG\StupidMQ\Queue\QueueInterface;

/**
 * RunnerInterface
 *
 * @author pierre
 */
interface RunnerInterface
{
    public function addWorker( WorkerInterface $worker );

    public function addQueue( QueueInterface $queue );

    public function run($queue_name, $message_id);
}
