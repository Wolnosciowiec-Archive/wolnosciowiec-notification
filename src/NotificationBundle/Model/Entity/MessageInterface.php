<?php

namespace NotificationBundle\Model\Entity;

/**
 * @package NotificationBundle\Model\Entity
 */
interface MessageInterface extends \Serializable
{
    /**
     * @notifyField Identifier, allows to find specific element. It's also an UNIQUE key for notifications.
     * @return string
     */
    public function getId(): string;

    /**
     * @notifyField Group name that allows to specify which Messengers would send it. Example: "content_update_notication" => Facebook and Twitter post creation, "alert" => "E-mail notification to admin"
     * @return string
     */
    public function getGroupName();

    /**
     * @notifyField Message text
     * @return string
     */
    public function getContent(): string;

    /**
     * @notifyField If the text is too long, then its length could be adjusted by the Messenger
     * @return bool
     */
    public function getCouldBeTruncated(): bool;
}