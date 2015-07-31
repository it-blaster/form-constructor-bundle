<?php

namespace Fenrizbes\FormConstructorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TemplateCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('fc.template.chain')) {
            return;
        }

        $chain    = $container->findDefinition('fc.template.chain');
        $services = $container->findTaggedServiceIds('fc.template');

        foreach ($services as $id => $tags) {
            foreach ($tags as $attributes) {
                $chain->addMethodCall('addTemplate', array(
                    new Reference($id),
                    $attributes['alias']
                ));
            }
        }
    }
}