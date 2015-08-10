<?php

namespace Fenrizbes\FormConstructorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class BehaviorConditionCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('fc.behavior.condition.chain')) {
            return;
        }

        $chain    = $container->findDefinition('fc.behavior.condition.chain');
        $services = $container->findTaggedServiceIds('fc.behavior.condition');

        foreach ($services as $id => $tags) {
            foreach ($tags as $attributes) {
                $chain->addMethodCall('addCondition', array(
                    new Reference($id),
                    $attributes['alias']
                ));
            }
        }
    }
}