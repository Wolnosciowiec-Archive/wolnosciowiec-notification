<?php declare(strict_types=1);

namespace Tests\NotificationBundle\Services;

use NotificationBundle\Model\Entity\Message\Message;
use NotificationBundle\Model\Entity\Result\SenderResult;
use Tests\NotificationBundle\TestCase;

class QueueCleanerServiceTest extends TestCase
{
    private function getService()
    {
        return $this->getContainer()->get('notificationbundle.services.cleaner.queue');
    }

    /**
     * @see QueueCleanerService::clearProcessedMessages()
     */
    public function testClearProcessedMessages()
    {
        // push example message to the queue
        $message = new Message('test');
        $this->getQueue()->push($message);

        $senderResult = new SenderResult(true);
        $senderResult->markAsDone($message);

        // the queue should contain only our item
        $this->assertArrayHasKey($message->getId(), $this->getQueue()->findAll());

        // execute the method that is tested
        $this->getService()->clearProcessedMessages($senderResult);

        // the queue should be empty
        $this->assertEmpty($this->getQueue()->findAll());
    }
}
