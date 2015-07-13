<?php

namespace Fenrizbes\FormConstructorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('fenrizbes_form_constructor');

        $rootNode
            ->children()
                ->arrayNode('defaults')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('date_format')
                            ->cannotBeEmpty()
                            ->isRequired()
                            ->defaultValue('d.m.Y')
                        ->end()
                        ->scalarNode('datetime_format')
                            ->cannotBeEmpty()
                            ->isRequired()
                            ->defaultValue('d.m.Y H:i')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
