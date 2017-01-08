<?php

namespace NotificationBundle\Queue;

use NotificationBundle\Model\Entity\MessageInterface;

/**
 * Interface for queue handler
 * The storage could be of any type
 * eg. Redis or SQLite3
 * --------------------------------
 *
 * @package NotificationBundle\Queue
 */
interface QueueInterface
{
    /**
     * Push a new item to the queue
     *
     * @param MessageInterface $message
     */
    public function push(MessageInterface $message);

    /**
     * Remove from queue by MessageInterface object
     *
     * @param MessageInterface $message
     */
    public function popOut(MessageInterface $message);

    /**
     * Remove from the queue by id
     *
     * @param string $id
     */
    public function popOutById(string $id);

    /**
     * List all items from queue
     *
     * @return MessageInterface[]
     */
    public function findAll();
}