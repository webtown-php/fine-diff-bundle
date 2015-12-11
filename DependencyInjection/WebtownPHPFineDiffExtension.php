<?php

namespace WebtownPHP\Bundle\FineDiffBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class WebtownPHPFineDiffExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if (!array_key_exists('default_granularity', $config)) {
            throw new InvalidConfigurationException('The `default_granularity` is required!');
        }

        $granularities = Configuration::getGranularities();
        foreach ($granularities as $key => $value) {
            $name = sprintf('webtown_php_fine_diff.granularity.%s', $key);
            $container->setParameter($name, $value);
        }
        $granularity = $granularities[$config['default_granularity']];
        $container->setParameter('webtown_php_fine_diff.default_granularity', $granularity);

        $definition = $container->getDefinition('webtown_php_fine_diff.twig.extension');
        $definition->replaceArgument(0, $granularity);
    }
}
