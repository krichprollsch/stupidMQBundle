<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *  30/11/12 10:02
 */

namespace CoG\StupidMQBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Symfony\Component\DependencyInjection\Definition;

/**
 * CoGSutpidMQCommand
 *
 * @author pierre
 */
class WatchQueueCommand extends AbstractCommand
{

    protected function configure()
    {
        $this
            ->setName('cog:stupidmq:watch')
            ->setDescription('Watcher to consume messages')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setOutputHandler($output);
        $watcher = $this->getContainer()->get('cog_stupidmq.watcher');
        $watcher->watch();
    }
}
