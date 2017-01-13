<?php declare(strict_types=1);

namespace NotificationBundle\Services;

use NotificationBundle\Factory\Queue\QueueFactory;
use NotificationBundle\Model\Entity\Result\SenderResult;

/**
 * @package NotificationBundle\Services
 */
class QueueCleanerService
{
    /** @var QueueFactory $factory */
    private $factory;

    /**
     * @param QueueFactory $factory
     */
    public function __construct(QueueFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param SenderResult $senderResult
     */
    public function clearProcessedMessages(SenderResult $senderResult)
    {
        foreach ($senderResult->getProcessedElements() as $sentId) {
            $this->factory->getQueue()->popOutById($sentId);
        }
    }
}