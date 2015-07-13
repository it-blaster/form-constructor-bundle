<?php

namespace Fenrizbes\FormConstructorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ListenerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('fc.listener.chain')) {
            return;
        }

        $chain    = $container->findDefinition('fc.listener.chain');
        $services = $container->findTaggedServiceIds('fc.listener');

        foreach ($services as $id => $tags) {
            foreach ($tags as $attributes) {
                $chain->addMethodCall('addListener', array(
                    new Reference($id),
                    $attributes['alias']
                ));
            }
        }
    }
}