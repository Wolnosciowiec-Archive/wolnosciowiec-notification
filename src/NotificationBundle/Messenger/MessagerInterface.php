<?php

namespace NotificationBundle\Messenger;

use NotificationBundle\Model\Entity\Exception\SendingException;
use NotificationBundle\Model\Entity\MessageInterface;

/**
 * @package NotificationBundle\SocialMedia
 */
interface MessengerInterface
{
    /**
     * @param MessageInterface $message
     * @throws SendingException
     * @return bool
     */
    public function send(MessageInterface $message): bool;
}