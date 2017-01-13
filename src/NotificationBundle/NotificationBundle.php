<?php

namespace NotificationBundle;

use NotificationBundle\DependencyInjection\Compiler\ConfigurationCompilerPass;
use NotificationBundle\DependencyInjection\NotificationExtension;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\VarDumper\VarDumper;

class NotificationBundle extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new NotificationExtension();
        }
        return $this->extension;
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new ConfigurationCompilerPass(), PassConfig::TYPE_AFTER_REMOVING);
    }
}
