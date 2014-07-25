<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *  30/11/12 10:02
 */

namespace CoG\StupidMQBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CoGSutpidMQCommand
 *
 * @author pierre
 */
class WatchQueueCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('cog:stupidmq:watch')
            ->setDescription('Watcher to consume messages')
            ->addOption( 'max-message', 'm', InputOption::VALUE_OPTIONAL, 'Max number of message treated before stop (0 for unlimited)', 0 )
            ->addOption( 'max-lifetime', 'l', InputOption::VALUE_OPTIONAL, 'Max lifetime before stop (0 for unlimited)', 0 )
            ->addOption( 'sleep', 'w', InputOption::VALUE_OPTIONAL, 'Time of sleeping in case of message found in queues', 1 )
            ->addOption( 'timeout', 't', InputOption::VALUE_OPTIONAL, 'Timeout for sub-processes (0 for unlimited)', 3600 )
            ->addOption( 'max-process', 'p', InputOption::VALUE_OPTIONAL, 'max number of processes running in parallel', 5 )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $watcher = $this->getContainer()->get('cog_stupidmq.watcher');
        $watcher->watch(array(
            'max-message' => $input->getOption('max-message'),
            'max-lifetime' => $input->getOption('max-lifetime'),
            'sleep' => $input->getOption('sleep'),
            'process-timeout' => $input->getOption('timeout'),
            'max-process' => $input->getOption('max-process')
        ));
    }
}
