<?php

namespace NotificationBundle\Messenger;

use NotificationBundle\Model\Entity\Exception\SendingException;
use NotificationBundle\Model\Entity\MessageInterface;

/**
 * @package NotificationBundle\Messenger
 */
class NullMessenger extends BaseMessenger implements MessengerInterface
{
    const EXCEPTION_TRIGGERING_MESSAGE_CONTENT = '#NullMessenger#Trigger#Exception#';
    const FAILING_MESSAGE_CONTENT = '#NullMesseger#FailingMessage#';

    /** @var MessageInterface[] $sentMessages */
    private $sentMessages = [];

    public function reconfigure()
    {
    }

    /**
     * @param MessageInterface $message
     *
     * @throws SendingException
     * @return bool
     */
    public function send(MessageInterface $message): bool
    {
        if ($message->getContent() === self::EXCEPTION_TRIGGERING_MESSAGE_CONTENT) {
            throw new SendingException('Oh no, cannot send that message');
        }

        if ($message->getContent() === self::FAILING_MESSAGE_CONTENT) {
            return false;
        }

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