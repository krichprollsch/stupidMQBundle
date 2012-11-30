<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *  30/11/12 10:02
 */

namespace CoG\StupidMQBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CoGSutpidMQCommand
 *
 * @author pierre
 */
abstract class AbstractCommand extends ContainerAwareCommand
{
    protected function setOutputHandler(OutputInterface $output) {
        if( $this->getContainer()->has('cog_stupidmq.cli.handler') ) {
            $handler = $this->getContainer()->get('cog_stupidmq.cli.handler');
            if( method_exists($handler, 'initialize') ) {
                $handler->initialize($output);
            }
        }
    }
}
