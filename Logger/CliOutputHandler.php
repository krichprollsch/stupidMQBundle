<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *  30/11/12 16:13
 */

namespace CoG\StupidMQBundle\Logger;

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Formatter\LineFormatter;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CliOutputHandler
 *
 * @author pierre
 */
class CliOutputHandler extends AbstractProcessingHandler
{
    private $output;

    public function __construct( Logger $logger, $subprocess=false ) {
        $this->prepareFormatter($subprocess);
        $logger->pushHandler($this);
    }

    public function initialize(OutputInterface $output)
    {
        $this->output = $output;
    }

    protected function write(array $record)
    {
        if($this->output) {
            $this->output->write( $record['formatted'] );
        }
    }

    public function prepareFormatter($subprocess=false) {
        $dateFormat = "Y-m-d H:i:s";
        if( $subprocess ) {
            $output = "[RUNNER]\t%message%";
        } else {
            $output = "%datetime%\t[%level_name%]\t%message%\n";
        }
        $formatter = new LineFormatter($output, $dateFormat);
        $this->setFormatter($formatter);
    }
}
