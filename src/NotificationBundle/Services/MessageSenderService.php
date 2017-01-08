<?php

namespace NotificationBundle\Services;

use NotificationBundle\Errors\SenderErrors;
use NotificationBundle\Factory\Messenger\MessengerFactory;
use NotificationBundle\Model\Entity\MessageInterface;
use NotificationBundle\Model\Entity\Result\SenderResult;
use NotificationBundle\Services\ConfigurationProvider\MessengerConfigurationProvider;
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

    /** @var MessengerConfigurationProvider $messengerConfiguration */
    private $messengerConfiguration;

    /**
     * @param MessengerFactory $factory
     * @param LoggerInterface $logger
     * @param MessengerConfigurationProvider $configuration
     */
    public function __construct(
        MessengerFactory $factory,
        LoggerInterface $logger,
        MessengerConfigurationProvider $configuration)
    {
        $this->factory                = $factory;
        $this->logger                 = $logger;
        $this->messengerConfiguration = $configuration;
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
    public function send(array $messages)
    {
        $this->logStatistics($messages);
        $result = new SenderResult();

        foreach ($messages as $message) {
            foreach ($this->factory->getMessengers() as $messenger) {

                // cannot be sent because of group
                if ($this->messengerConfiguration
                    ->isGroupAllowedForMessenger($message->getGroupName(), $messenger) === false) {

                    // logging
                    $this->logger->info(
                        'Marking message "' . $message->getId() . '" as sent for "' .
                        get_class($messenger) . '" as the group does not match');

                    $result->markAsDone($message, true);
                    continue;
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
                }
            }
        }

        return $result;
    }
}
