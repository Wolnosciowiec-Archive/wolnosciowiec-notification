<?php declare(strict_types = 1);

namespace NotificationBundle\Model\Entity\Message;
use NotificationBundle\Model\Entity\MessageInterface;

/**
 * @package NotificationBundle\Model\Entity\Message\Message
 */
interface MailMessageInterface extends MessageInterface
{
    /**
     * @notifyField Mail subject
     * @return string
     */
    public function getSubject() : string;

    /**
     * @notifyField Name and e-mail address of the message sender
     * @return string
     */
    public function getFrom() : string;

    /**
     * @notifyField List of recipients, format like in getFrom()
     * @return array
     */
    public function getRecipients() : array;
}