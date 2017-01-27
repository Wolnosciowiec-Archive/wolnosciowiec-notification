<?php

namespace NotificationBundle\DependencyInjection\Compiler;

use Symfony\Component\Config\Resource\DirectoryResource;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Finder\Finder;

/**
 * @codeCoverageIgnore
 * @package NotificationBundle\DependencyInjection\Compiler
 */
class ValidatorPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $validatorBuilder = $container->getDefinition('validator.builder');
        $validatorFiles = array();
        $finder = new Finder();

        foreach ($finder->files()->in(__DIR__ . '/../../Resources/config/validation') as $file) {
            $validatorFiles[] = $file->getRealPath();
        }

        $validatorBuilder->addMethodCall('addYamlMappings', array($validatorFiles));

        // add resources to the container to refresh cache after updating a file
        $container->addResource(new DirectoryResource(__DIR__ . '/../../Resources/config/validation'));
    }
}
