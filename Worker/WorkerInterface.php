<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *  30/11/12 09:26
 */

namespace CoG\StupidMQBundle\Worker;

/**
 * WorkerInterface
 *
 * @author pierre
 */
interface WorkerInterface
{
    /**
     * @param string $message
     * @return boolean
     */
    public function execute( $message );

    /**
     * @return array
     */
    public function getSubscribedQueues();

    /**
     * @return string
     */
    public function getName();
}
