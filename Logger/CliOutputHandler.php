<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *  30/11/12 16:13
 */

namespace CoG\StupidMQBundle\Logger;

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CliOutputHandler
 *
 * @author pierre
 */
class CliOutputHandler extends AbstractProcessingHandler
{
    private $output;

    public function __construct( Logger $logger ) {
        $logger->pushHandler($this);
    }

    public function initialize(OutputInterface $output)
    {
        $this->output = $output;
    }

    protected function write(array $record)
    {
        if($this->output) {
            $this->output->writeln( $record['formatted'] );
        }
    }
}
