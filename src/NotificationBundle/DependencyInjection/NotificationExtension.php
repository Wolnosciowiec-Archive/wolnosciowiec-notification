<?php

namespace NotificationBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @package Wolnosciowiec\FileRepositoryBundle\DependencyInjection
 */
class NotificationExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);
        $definitions   = [];

        foreach (array_keys($config['enabled_messengers']) as $messenger) {
            $definitions[] = new Reference(str_replace('@', '', $messenger));
        }

        // push services to the factory so it could serve them to the application
        $container->getDefinition('notificationbundle.factory.messenger')->addMethodCall('setServices', [$definitions]);

        // push queue service to the queue factory
        $container->getDefinition('notificationbundle.factory.queue')->addMethodCall(
            'setQueueService',
            [new Reference(str_replace('@', '', $config['queue']))]
        );

        // inject allowed classes list (entities list)
        $container->getDefinition('notificationbundle.factory.message')->addMethodCall(
            'setAllowedClasses',
            [$config['allowed_entities']]
        );

        // pass the configuration
        $container->getDefinition('notificationbundle.services.configuration.messenger')->addMethodCall(
            'setMessengersConfiguration',
            [$config['messengers'], $config['enabled_messengers']]
        );
        $container->getDefinition('notificationbundle.services.configuration.queue')->addMethodCall(
            'setAllConfiguration',
            [$config['queue_parameters']]
        );
    }
}