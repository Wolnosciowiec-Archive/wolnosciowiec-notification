<?php declare(strict_types=1);

namespace Tests\NotificationBundle\Services;

use NotificationBundle\Messenger\NullMessenger;
use NotificationBundle\Model\Entity\Message\Message;
use NotificationBundle\Services\MessageSenderService;
use Tests\NotificationBundle\TestCase;

/**
 * @see MessageSenderService
 */
class MessageSenderServiceTest extends TestCase
{
    /**
     * @see MessageSenderService::sendMultiple()
     * @see MessageSenderService::send()
     */
    public function testSendMultiple()
    {
        /** @var Message[] $messages */
        $messages = [
            (new Message('first'))->setGroupName('example_1'),
            (new Message('second'))->setGroupName('not_matching'),
        ];

        $result = $this->getService()->sendMultiple($messages);

        $this->assertContains($messages[0]->getId(), $result->getProcessedElements());
        $this->assertSame(2, $result->getDoneCount());
    }

    /**
     * @see MessageSenderService::sendMultiple()
     * @see MessageSenderService::send()
     */
    public function testFailureSendMultiple()
    {
        /** @var Message[] $failingMessages */
        $failingMessages = [
            (new Message(NullMessenger::EXCEPTION_TRIGGERING_MESSAGE_CONTENT))->setGroupName('example_1'),
            (new Message(NullMessenger::FAILING_MESSAGE_CONTENT))->setGroupName('example_1'),
        ];

        $result = $this->getService()->sendMultiple($failingMessages);

        foreach ($failingMessages as $failingMessage) {
            $this->assertArrayHasKey($failingMessage->getId(), $result->getFailureCodes());
            $this->assertContains($failingMessage->getId(), $result->getProcessedElements());
        }

        $this->assertSame(2, $result->getDoneCount());
        $this->assertFalse($result->isSuccess());
    }

    private function getService(): MessageSenderService
    {
        return $this->getContainer()->get('notificationbundle.services.sender');
    }
}