<?php

namespace NotificationBundle\Services\ConfigurationProvider;

/**
 * Gets configuration from config.yml or notification.yml
 * and serves to other services.
 * Injected in the extension (Dependency injection)
 *
 * @package NotificationBundle\Services\ConfigurationProvider
 */
abstract class AbstractConfigurationProvider
{
    /** @var array $configuration */
    private $configuration = [];

    /**
     * @param array $configuration
     * @return $this
     */
    public function setAllConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
        return $this;
    }

    /**
     * @param string $section
     * @return array
     */
    public function getConfiguration($section)
    {
        if (!isset($this->configuration[$section])) {
            throw new \InvalidArgumentException(get_class() . ': Invalid section name specified, should match the one in notification.yml');
        }

        return $this->configuration[$section];
    }

    /**
     * @param string $variable
     * @param string $section
     */
    public function get($variable, $section)
    {
        $configuration = $this->getConfiguration($section);

        if (!isset($configuration[$variable])) {
            throw new \InvalidArgumentException(get_class() . ': Invalid option "' . $variable . '", not specified in config under "' . $section . '"');
        }

        return $configuration[$variable];
    }
}