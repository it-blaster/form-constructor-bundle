<?php

namespace Fenrizbes\FormConstructorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ConstraintCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('fc.constraint.chain')) {
            return;
        }

        $chain    = $container->findDefinition('fc.constraint.chain');
        $services = $container->findTaggedServiceIds('fc.constraint');

        foreach ($services as $id => $tags) {
            foreach ($tags as $attributes) {
                $chain->addMethodCall('addConstraint', array(
                    new Reference($id),
                    $attributes['alias']
                ));
            }
        }
    }
}