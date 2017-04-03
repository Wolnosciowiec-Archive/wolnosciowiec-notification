<?php declare(strict_types=1);

namespace NotificationBundle\Factory\Messenger;

use NotificationBundle\Messenger\MessengerInterface;
use NotificationBundle\Services\ConfigurationProvider\MessengerConfiguration;

/**
 * @package NotificationBundle\Factory\Messenger
 */
class MessengerFactory
{
    /**
     * @var MessengerInterface[] $services
     */
    private $services;

    /**
     * @return MessengerInterface[]
     */
    public function getMessengers()
    {
        return $this->services;
    }

    /**
     * @param MessengerInterface[] $services
     * @return $this
     */
    public function setServices($services)
    {
        $constructed = [];

        foreach ($services as $service) {
            /**
             * @var MessengerInterface $instance
             */
            $instance = $service[0];
            $config = new MessengerConfiguration($service[1]);

            $instance->setConfiguration($config);
            $constructed[] = $instance;
        }

        $this->services = $constructed;
        return $this;
    }
}
