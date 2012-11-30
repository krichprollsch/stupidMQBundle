<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *  30/11/12 10:39
 */

namespace CoG\StupidMQBundle\Watcher;

use CoG\StupidMQ\Queue\QueueInterface;

/**
 * WatcherInterface
 *
 * @author pierre
 */
interface WatcherInterface
{
    public function addQueue( QueueInterface $queue );

    public function watch();
}
