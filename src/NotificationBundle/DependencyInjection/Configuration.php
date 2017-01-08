<?php

namespace NotificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
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
            ->children()
                ->arrayNode('messengers')
                ->ignoreExtraKeys(false)
                ->isRequired()
                ->children()
                    ->arrayNode('twitter')
                        ->isRequired()
                        ->children()
                            ->scalarNode('consumer_key')
                            ->end()
                        ->end()
                        ->children()
                            ->scalarNode('consumer_secret')
                            ->end()
                        ->end()
                        ->children()
                            ->scalarNode('access_token')
                            ->end()
                        ->end()
                        ->children()
                            ->scalarNode('access_token_secret')
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->children()
                    ->arrayNode('facebook')
                        ->isRequired()
                        ->children()
                            ->scalarNode('app_id')
                            ->end()
                        ->end()
                        ->children()
                            ->scalarNode('app_secret')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}