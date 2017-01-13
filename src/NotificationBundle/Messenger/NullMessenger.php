<?php

namespace NotificationBundle\Messenger;

use NotificationBundle\Model\Entity\MessageInterface;

/**
 * @package NotificationBundle\Messenger
 */
class NullMessenger implements MessengerInterface
{
    /** @var MessageInterface[] $sentMessages */
    private $sentMessages = [];

    /**
     * @param MessageInterface $message
     * @return bool
     */
    public function send(MessageInterface $message): bool
    {
        $this->sentMessages[] = $message;
        return true;
    }

    /**
     * @return \NotificationBundle\Model\Entity\MessageInterface[]
     */
    public function getSentMessages()
    {
        return $this->sentMessages;
    }
}