<?php declare(strict_types=1);

namespace NotificationBundle\Messenger;

use NotificationBundle\Model\Entity\MessageInterface;
use Psr\Log\LoggerInterface;

class TwitterMessenger extends BaseMessenger implements MessengerInterface
{
    /** @var \Twitter $client */
    private $client;

    /** @var LoggerInterface $logger */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function reconfigure()
    {
        $this->client = new \Twitter(
            $this->getConfig()->get('consumer_key'),
            $this->getConfig()->get('consumer_secret'),
            $this->getConfig()->get('access_token'),
            $this->getConfig()->get('access_token_secret')
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

        }  catch (\TwitterException $e) {
            $this->logger->error(
                'Got \\TwitterException: "' . $e->getMessage() .
                '", message id=' . $message->getId());

            throw $e;
        }

        return true;
    }
}