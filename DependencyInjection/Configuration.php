<?php

namespace WebtownPHP\Bundle\FineDiffBundle\DependencyInjection;

use GorHill\FineDiff\FineDiff;
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
        $rootNode = $treeBuilder->root('webtown_php_fine_diff');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
            ->children()
                ->enumNode('default_granularity')
                    ->values(array_keys(self::getGranularities()))
                    ->defaultValue('character')
                ->end()
            ->end()
        ;


        return $treeBuilder;
    }

    public static function getGranularities()
    {
        return [
            'character' => FineDiff::$characterGranularity,
            'word'      => FineDiff::$wordGranularity,
            'sentence'  => FineDiff::$sentenceGranularity,
            'paragraph' => FineDiff::$paragraphGranularity,
        ];
    }
}
