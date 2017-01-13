<?php

namespace NotificationBundle\Factory\Messenger;

use NotificationBundle\Messenger\MessengerInterface;

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
        $this->services = $services;
        return $this;
    }
}