<?php

namespace NotificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @codeCoverageIgnore
 * @package NotificationBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Tells the framework that we need to register a group
     * of configuration, that is required for this bundle to work
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('notification');

        $rootNode
            ->children()
                ->arrayNode('enabled_messengers')
                    ->ignoreExtraKeys(false)
                    ->isRequired()
                ->end()
            ->end()
            ->children()
                ->arrayNode('allowed_entities')
                    ->ignoreExtraKeys(false)
                    ->isRequired()
                ->end()
            ->end()
            ->children()
                ->scalarNode('queue')
                    ->isRequired()
                ->end()
            ->end()
            ->children()
                ->arrayNode('queue_parameters')
                    ->ignoreExtraKeys(false)
                    ->isRequired()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}