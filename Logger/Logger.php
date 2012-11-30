<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *  30/11/12 16:32
 */

namespace CoG\StupidMQBundle\Logger;

use Symfony\Component\HttpKernel\Log\LoggerInterface;

/**
 * Logger
 *
 * @author pierre
 */
interface Logger
{
    /**
     * @param \Symfony\Component\HttpKernel\Log\LoggerInterface $logger
     * @return void
     */
    public function setLogger( LoggerInterface $logger );

    /**
     * @return LoggerInterface
     */
    public function getLogger();

    public function log( $message );
}
