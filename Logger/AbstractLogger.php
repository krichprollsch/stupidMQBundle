<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *  30/11/12 16:33
 */

namespace CoG\StupidMQBundle\Logger;

use Symfony\Component\HttpKernel\Log\LoggerInterface;

/**
 * AbstractLogger
 *
 * @author pierre
 */
class AbstractLogger
{
    protected $logger;

    /**
     * @param \Symfony\Component\HttpKernel\Log\LoggerInterface $logger
     * @return void
     */
    public function setLogger( LoggerInterface $logger ) {
        $this->logger = $logger;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger() {
        return $this->logger;
    }

    public function log( $message, $level='info', $context=array() ) {
        if($this->getLogger()) {
            if(method_exists($this->getLogger(), $level)) {
                $this->getLogger()->$level( $message, $context );
            } else {
                throw new \InvalidArgumentException(sprintf('Level %s is not a valid log level', $level));
            }
        }
    }
}
