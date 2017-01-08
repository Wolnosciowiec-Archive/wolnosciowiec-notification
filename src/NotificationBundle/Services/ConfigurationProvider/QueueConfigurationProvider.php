<?php

namespace NotificationBundle\Services\ConfigurationProvider;

/**
 * Queue parameters provider (eg. connection host, password)
 *
 * @package NotificationBundle\Services
 */
class QueueConfigurationProvider
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
     * @return array
     */
    public function getAll()
    {
        return $this->configuration;
    }

    /**
     * @param string $variable
     * @param string $section
     *
     * @return array|string
     */
    public function get($variable, $section)
    {
        if (!isset($this->configuration[$variable])) {
            throw new \InvalidArgumentException(get_class() . ': Invalid option "' . $variable . '", not specified in config under "' . $section . '"');
        }

        return $this->configuration[$variable];
    }
}