<?php

namespace NotificationBundle\Model\Entity;

/**
 * @package NotificationBundle\Model\Entity
 */
interface MessageInterface extends \Serializable
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getGroupName();

    /**
     * @return string
     */
    public function getContent(): string;
}