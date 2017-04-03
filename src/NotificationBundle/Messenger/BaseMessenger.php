<?php declare(strict_types=1);

namespace NotificationBundle\Messenger;

use NotificationBundle\Services\ConfigurationProvider\MessengerConfiguration;

abstract class BaseMessenger implements MessengerInterface
{
    private $_configuration = '';

    public function setConfiguration(MessengerConfiguration $configuration): MessengerInterface
    {
        // be immutable
        if (!empty($this->_configuration)) {
            throw new \LogicException('The configuration should be set only once, don\'t try to do any tricks');
        }

        $this->_configuration = $configuration;
        $this->reconfigure();

        return $this;
    }

    public function getConfig(): MessengerConfiguration
    {
        return $this->_configuration;
    }
}
