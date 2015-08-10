<?php

namespace Fenrizbes\FormConstructorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class BehaviorActionCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('fc.behavior.action.chain')) {
            return;
        }

        $chain    = $container->findDefinition('fc.behavior.action.chain');
        $services = $container->findTaggedServiceIds('fc.behavior.action');

        foreach ($services as $id => $tags) {
            foreach ($tags as $attributes) {
                $chain->addMethodCall('addAction', array(
                    new Reference($id),
                    $attributes['alias']
                ));
            }
        }
    }
}