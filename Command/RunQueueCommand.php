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
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CoGSutpidMQCommand
 *
 * @author pierre
 */
class RunQueueCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('cog:stupidmq:run')
            ->setDescription('Runner to process message')
            ->addArgument('queue', InputArgument::REQUIRED, 'Queue name to process' )
            ->addArgument('message-id', InputArgument::REQUIRED, 'Message id to process' )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $runner = $this->getContainer()->get('cog_stupidmq.runner');
        $runner->run( $input->getArgument('queue'), $input->getArgument('message-id')  );
    }

}
