<?php

namespace Fenrizbes\FormConstructorBundle;

use Fenrizbes\FormConstructorBundle\DependencyInjection\Compiler\BehaviorCompilerPass;
use Fenrizbes\FormConstructorBundle\DependencyInjection\Compiler\FieldCompilerPass;
use Fenrizbes\FormConstructorBundle\DependencyInjection\Compiler\ConstraintCompilerPass;
use Fenrizbes\FormConstructorBundle\DependencyInjection\Compiler\ListenerCompilerPass;
use Fenrizbes\FormConstructorBundle\DependencyInjection\Compiler\TemplateCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FenrizbesFormConstructorBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container
            ->addCompilerPass(new FieldCompilerPass())
            ->addCompilerPass(new ConstraintCompilerPass())
            ->addCompilerPass(new ListenerCompilerPass())
            ->addCompilerPass(new TemplateCompilerPass())
            ->addCompilerPass(new BehaviorCompilerPass())
        ;
    }
}
