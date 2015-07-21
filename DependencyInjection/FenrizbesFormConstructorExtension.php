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
        $bundles = $container->getParameter('kernel.bundles');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('fields.yml');
        $loader->load('constraints.yml');
        $loader->load('listeners.yml');

        $loader->load('form_types.yml');
        $loader->load('services.yml');
        $loader->load('twig.yml');
        $loader->load('sonata_admin.yml');

        if (isset($bundles['IvoryCKEditorBundle'])) {
            $config['defaults']['text_editor'] = 'ckeditor';
        }

        $container->setParameter('fc.defaults', $config['defaults']);
    }
}
