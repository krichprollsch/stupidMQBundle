<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *  30/11/12 10:39
 */

namespace CoG\StupidMQBundle\Watcher;

use CoG\StupidMQBundle\QueueAware\QueueAwareInterface;

/**
 * WatcherInterface
 *
 * @author pierre
 */
interface WatcherInterface extends QueueAwareInterface
{
    const UNLIMITED = 0;

    public function watch(array $opt = array());
}
