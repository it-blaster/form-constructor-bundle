<?php

namespace Fenrizbes\FormConstructorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class BehaviorCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('fc.behavior.chain')) {
            return;
        }

        $chain    = $container->findDefinition('fc.behavior.chain');
        $services = $container->findTaggedServiceIds('fc.behavior');

        foreach ($services as $id => $tags) {
            foreach ($tags as $attributes) {
                $chain->addMethodCall('addBehavior', array(
                    new Reference($id),
                    $attributes['alias']
                ));
            }
        }
    }
}