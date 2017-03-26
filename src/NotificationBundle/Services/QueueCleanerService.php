<?php declare(strict_types=1);

namespace NotificationBundle\Services;

use NotificationBundle\Factory\Queue\QueueFactory;
use NotificationBundle\Model\Entity\Result\SenderResult;
use Psr\Log\LoggerInterface;

/**
 * @package NotificationBundle\Services
 */
class QueueCleanerService
{
    /** @var QueueFactory $factory */
    private $factory;

    /** @var LoggerInterface $loggers */
    private $logger;

    public function __construct(QueueFactory $factory, LoggerInterface $logger)
    {
        $this->factory = $factory;
        $this->logger  = $logger;
    }

    /**
     * @param SenderResult $senderResult
     */
    public function clearProcessedMessages(SenderResult $senderResult)
    {
        $this->logger->info(
            '[Cleaner] Got results from sender: ' .
            json_encode($senderResult->getProcessedElements()));

        foreach ($senderResult->getProcessedElements() as $sentId) {
            $this->logger->info('[Cleaner] Popping out element ' . $sentId);
            $this->factory->getQueue()->popOutById($sentId);
        }
    }
}