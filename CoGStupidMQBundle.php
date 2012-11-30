<?php

namespace CoG\StupidMQBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use CoG\StupidMQBundle\DependencyInjection\CoGStupidMQCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CoGStupidMQBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new CoGStupidMQCompilerPass());
    }
}
