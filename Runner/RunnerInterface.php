<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *  30/11/12 09:37
 */

namespace CoG\StupidMQBundle\Runner;

use CoG\StupidMQBundle\QueueAware\QueueAwareInterface;
use CoG\StupidMQBundle\Worker\WorkerInterface;

/**
 * RunnerInterface
 *
 * @author pierre
 */
interface RunnerInterface extends QueueAwareInterface
{
    public function addWorker(WorkerInterface $worker);

    public function run($queue_name, $message_id);
}
