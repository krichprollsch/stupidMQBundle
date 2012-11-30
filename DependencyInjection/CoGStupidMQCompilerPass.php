<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *  30/11/12 09:39
 */

namespace CoG\StupidMQBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * CoGStupidMQCompilerPass
 *
 * @author pierre
 */
class CoGStupidMQCompilerPass  implements CompilerPassInterface
{
    const WORKER_TAG_NAME = 'cog_stupidmq.worker';
    const QUEUE_TAG_NAME = 'cog_stupidmq.queue';
    const RUNNER_ID = 'cog_stupidmq.runner';
    const WATCHER_ID = 'cog_stupidmq.watcher';

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(self::RUNNER_ID)) {
            return;
        }

        $runner = $container->getDefinition(
            self::RUNNER_ID
        );

        if (!$container->hasDefinition(self::WATCHER_ID)) {
            return;
        }


        $watcher = $container->getDefinition(
            self::WATCHER_ID
        );

        $queues = $container->findTaggedServiceIds(
            self::QUEUE_TAG_NAME
        );
        foreach ($queues as $id => $attributes) {
            $runner->addMethodCall(
                'addQueue',
                array(new Reference($id))
            );

            $watcher->addMethodCall(
                'addQueue',
                array(new Reference($id))
            );
        }

        $workers = $container->findTaggedServiceIds(
            self::WORKER_TAG_NAME
        );
        foreach ($workers as $id => $attributes) {
            $runner->addMethodCall(
                'addWorker',
                array(new Reference($id))
            );
        }
    }
}
