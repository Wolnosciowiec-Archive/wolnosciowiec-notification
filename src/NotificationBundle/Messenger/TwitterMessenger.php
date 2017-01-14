<?php declare(strict_types=1);

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
     * @param LoggerInterface                $logger
     */
    public function __construct(
        MessengerConfigurationProvider $configurationProvider,
        LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->client = new \Twitter(
            $configurationProvider->get('consumer_key', 'twitter'),
            $configurationProvider->get('consumer_secret', 'twitter'),
            $configurationProvider->get('access_token', 'twitter'),
            $configurationProvider->get('access_token_secret', 'twitter')
        );
    }

    /**
     * @param MessageInterface $message
     * @return MessageInterface|string
     */
    private function correctMessageContent(MessageInterface $message): string
    {
        // in first order use the "title" if it is specified
        if (strlen($message->getTitle()) > 0) {
            return substr($message->getTitle(), 0, 140);
        }

        // fallback to using "content" (full message)
        if ($message->getCouldBeTruncated() === true) {
            return substr($message->getContent(), 0, 140);
        }

        return $message->getContent();
    }


    /**
     * @inheritdoc
     */
    public function send(MessageInterface $message): bool
    {
        $content = $this->correctMessageContent($message);

        try {
            $this->client->send($content);
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