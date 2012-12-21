<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *  30/11/12 16:33
 */

namespace CoG\StupidMQBundle\Logger;

use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;

/**
 * AbstractLogger
 *
 * @author pierre
 */
class AbstractLogger implements Logger
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ConsoleOutputInterface
     */
    protected $console;

    /**
     * @param LoggerInterface $logger
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

    /**
     * @param ConsoleOutputInterface $console
     * @return void
     */
    public function setConsoleOutput( ConsoleOutputInterface $console ) {
        $this->console = $console;
    }

    /**
     * @return ConsoleOutputInterface
     */
    public function getConsoleOutput() {
        return $this->console;
    }

    public function log( $message, $level='info', $context=array() ) {
        if($this->getLogger()) {
            if(method_exists($this->getLogger(), $level)) {
                $this->getLogger()->$level( $message, $context );
            } else {
                throw new \InvalidArgumentException(sprintf('Level %s is not a valid log level', $level));
            }
        }

        if($this->getConsoleOutput()) {
            $this->getConsoleOutput()->writeln(trim($message));
        }
    }
}
