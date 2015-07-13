<?php

namespace Fenrizbes\FormConstructorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FieldCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('fc.field.chain')) {
            return;
        }

        $chain    = $container->findDefinition('fc.field.chain');
        $services = $container->findTaggedServiceIds('fc.field');

        foreach ($services as $id => $tags) {
            foreach ($tags as $attributes) {
                $chain->addMethodCall('addField', array(
                    new Reference($id),
                    $attributes['alias']
                ));
            }
        }
    }
}