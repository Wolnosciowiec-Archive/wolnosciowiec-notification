<?php declare(strict_types=1);

namespace NotificationBundle\Services\Processor;

use NotificationBundle\Factory\Queue\QueueFactory;
use NotificationBundle\Model\Entity\MessageInterface;
use NotificationBundle\Model\Entity\Result\SenderResult;
use NotificationBundle\Services\MessageSenderService;
use NotificationBundle\Services\QueueCleanerService;

/**
 * @package NotificationBundle\Services\Processor
 */
class QueueProcessor
{
    /** @var MessageSenderService $sender */
    private $sender;

    /** @var QueueCleanerService $cleaner */
    private $cleaner;

    /** @var QueueFactory $factory */
    private $factory;

    /**
     * @param MessageSenderService $sender
     * @param QueueCleanerService  $cleaner
     * @param QueueFactory         $factory
     */
    public function __construct(
        MessageSenderService $sender,
        QueueCleanerService $cleaner,
        QueueFactory        $factory)
    {
        $this->sender  = $sender;
        $this->cleaner = $cleaner;
        $this->factory = $factory;
    }

    /**
     * @return SenderResult
     */
    public function process()
    {
        /** @var MessageInterface[] $messages */
        $messages = array_filter($this->factory->getQueue()->findAll());

        $results = $this->sender->sendMultiple($messages);
        $this->cleaner->clearProcessedMessages($results);

        return $results;
    }
}
