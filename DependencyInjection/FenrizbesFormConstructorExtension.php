<?php

namespace Fenrizbes\FormConstructorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class FenrizbesFormConstructorExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('fields.yml');
        $loader->load('constraints.yml');
        $loader->load('listeners.yml');

        $loader->load('services.yml');
        $loader->load('twig.yml');
        $loader->load('sonata_admin.yml');

        $container->setParameter('fc.defaults', $config['defaults']);

        $fields_types = $container->getParameter('fc.fields_types');
        $constrains   = $container->getParameter('fc.constraints');
        $listeners    = $container->getParameter('fc.listeners');

        foreach ($config['fields_types'] as $name => $field_type) {
            $fields_types[$name] = $field_type;
        }

        foreach ($config['constraints'] as $name => $constraint) {
            $constrains[$name] = $constraint;
        }

        foreach ($config['listeners'] as $name => $listener) {
            $listeners[$name] = $listener;
        }

        $container->setParameter('fc.fields_types', $fields_types);
        $container->setParameter('fc.constraints', $constrains);
        $container->setParameter('fc.listeners', $listeners);
        $container->setParameter('fc.listeners', $listeners);
    }
}
