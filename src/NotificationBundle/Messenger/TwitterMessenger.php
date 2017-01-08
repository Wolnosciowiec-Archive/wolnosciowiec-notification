<?php

namespace NotificationBundle\Messenger;

use NotificationBundle\Model\Entity\MessageInterface;
use NotificationBundle\Services\ConfigurationProvider\MessengerConfigurationProvider;
use Psr\Log\LoggerInterface;

/**
 * @package NotificationBundle\Messenger
 */
class TwitterMessenger implements MessengerInterface
{
    /** @var \Twitter $client */
    private $client;

    /** @var LoggerInterface $logger */
    private $logger;

    /**
     * @param MessengerConfigurationProvider $configurationProvider
     */
    public function __construct(
        MessengerConfigurationProvider $configurationProvider,
        LoggerInterface $logger)
    {
        $this->client = new \Twitter(
            $configurationProvider->get('consumer_key', 'twitter'),
            $configurationProvider->get('consumer_secret', 'twitter'),
            $configurationProvider->get('access_token', 'twitter'),
            $configurationProvider->get('access_token_secret', 'twitter')
        );
    }

    /**
     * @inheritdoc
     */
    public function send(MessageInterface $message): bool
    {
        try {
            $this->client->send($message->getContent());
        }
        catch (\TwitterException $e) {
            $this->logger->error(
                'Got \\TwitterException: "' . $e->getMessage() .
                '", message id=' . $message->getId());

            throw $e;
        }

        return true;
    }
}