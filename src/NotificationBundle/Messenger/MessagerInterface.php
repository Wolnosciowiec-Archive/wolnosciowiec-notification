<?php

namespace NotificationBundle\Messenger;

use NotificationBundle\Model\Entity\Exception\SendingException;
use NotificationBundle\Model\Entity\MessageInterface;
use NotificationBundle\Services\ConfigurationProvider\MessengerConfiguration;

interface MessengerInterface
{
    /**
     * @param MessageInterface $message
     * @throws SendingException
     * @return bool
     */
    public function send(MessageInterface $message): bool;

    /**
     * This gives every instance a SEPARATE configuration.
     * And so allows multiple instances of same messengers.
     *
     * eg. twitter, twitter_second_account
     *
     * @param MessengerConfiguration $configuration
     * @return MessengerInterface
     */
    public function setConfiguration(MessengerConfiguration $configuration): MessengerInterface;

    /**
     * Runs after the setConfiguration()
     * Used to refresh configuration
     */
    public function reconfigure();

    /**
     * Provide self configuration
     *
     * @return MessengerConfiguration
     */
    public function getConfig(): MessengerConfiguration;
}
