<?php declare(strict_types=1);

namespace NotificationBundle\Services;

use NotificationBundle\Errors\SenderErrors;
use NotificationBundle\Factory\Messenger\MessengerFactory;
use NotificationBundle\Messenger\MessengerInterface;
use NotificationBundle\Model\Entity\MessageInterface;
use NotificationBundle\Model\Entity\Result\SenderResult;
use Psr\Log\LoggerInterface;

/**
 * Uses factory to get services enabled in config.yml
 * and executes a sending action on them
 *
 * @package NotificationBundle\Services
 */
class MessageSenderService
{
    /** @var MessengerFactory $factory */
    private $factory;

    /** @var LoggerInterface $logger */
    private $logger;

    /**
     * @param MessengerFactory $factory
     * @param LoggerInterface $logger
     */
    public function __construct(
        MessengerFactory $factory,
        LoggerInterface $logger
    ) {
        $this->factory                = $factory;
        $this->logger                 = $logger;
    }

    /**
     * @param array $messages
     */
    private function logStatistics(array $messages)
    {
        $this->logger->info('Received ' . count($messages) . ' to send');
    }

    /**
     * @param MessageInterface[] $messages
     * @return SenderResult
     */
    public function sendMultiple(array $messages)
    {
        $this->logStatistics($messages);
        $result = new SenderResult();

        foreach ($messages as $message) {
            foreach ($this->factory->getMessengers() as $messenger) {
                $this->send($messenger, $message, $result);
            }
        }

        return $result;
    }

    public function send(MessengerInterface $messenger, MessageInterface $message, SenderResult $result)
    {
        $this->logger->info('Trying Messenger=' . get_class($messenger) . ' for Message=' . $message->getId());

        // cannot be sent because of group
        if ($messenger->getConfig()->isGroupAllowedForMessenger($message) === false) {

            // logging
            $this->logger->info(
                'Marking message "' . $message->getId() . '" as sent for "' .
                get_class($messenger) . '" as the group does not match');

            $result->markAsDone($message, true);
            return;
        }

        try {
            $messenger->send($message);
            $result->markAsDone($message);

            // logging
            $this->logger->info('Message id="' . $message->getId() . '" sent using "' . get_class($messenger) . '"');

        } catch (\Exception $e) {
            $result->addFailureCode($message->getId(), SenderErrors::SENDING_FAILURE);
            $result->markAsDone($message);

            // logging
            $this->logger->error('Cannot send a message, id="' . $message->getId()
                . '", sender="' . get_class($messenger) . '" exception message: "' . $e->getMessage() . '"'
            );

            $this->logger->error($e);
        }
    }
}
