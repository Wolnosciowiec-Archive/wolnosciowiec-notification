<?php

namespace NotificationBundle\Factory\Queue;

use NotificationBundle\Queue\QueueInterface;

/**
 * @package NotificationBundle\Factory\Queue
 */
class QueueFactory
{
    /** @var QueueInterface $queueService */
    private $queueService;

    /**
     * @param QueueInterface $queue
     * @return $this
     */
    public function setQueueService(QueueInterface $queue)
    {
        $this->queueService = $queue;
        return $this;
    }

    /**
     * @return QueueInterface
     */
    public function getQueue(): QueueInterface
    {
        return $this->queueService;
    }
}