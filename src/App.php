<?php

namespace App;

use App\DependencyInjection\Compiler\ExceptionNormalizerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class App extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ExceptionNormalizerPass());
    }
}