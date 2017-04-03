<?php

namespace NotificationBundle\Queue;

use NotificationBundle\Model\Entity\MessageInterface;

/**
 * A queue implementation for testing purposes
 * -------------------------------------------
 * Data in it is per request (it's ok for integration tests inside of PhpUnit)
 *
 * @package NotificationBundle\Queue
 */
class NullQueue implements QueueInterface
{
    /** @var array $queue */
    private $queue = [];

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->queue;
    }

    /**
     * @inheritdoc
     */
    public function push(MessageInterface $message)
    {
        $this->queue[$message->getId()] = $message;
        return isset($this->queue[$message->getId()]);
    }

    /**
     * @inheritdoc
     */
    public function popOutById(string $id)
    {
        if (isset($this->queue[$id])) {
            unset($this->queue[$id]);
        }

        return isset($this->queue[$id]) === false;
    }

    /**
     * @inheritdoc
     */
    public function popOut(MessageInterface $message)
    {
        return $this->popOutById($message->getId());
    }
}