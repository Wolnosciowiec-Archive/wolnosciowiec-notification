<?php declare(strict_types=1);

namespace Tests\NotificationBundle\Messenger;

use Monolog\Logger;
use NotificationBundle\Messenger\SlackMessenger;
use NotificationBundle\Model\Entity\Message\SlackMessage;
use Tests\NotificationBundle\TestCase;

/**
 * @see SlackMessenger
 */
class SlackMessengerTest extends TestCase
{
    /**
     * Test sending if the SlackMessage type could be properly used
     *
     * @see SlackMessenger::send()
     */
    public function testSendWithExtendedInformation()
    {
        $builder = $this->getMockBuilder(SlackMessage::class);
        $builder->setMethods(['getAllowMarkdown', 'getHookUrl', 'getIcon']);
        $message = $builder->getMock();

        $message->method('getHookUrl')
            ->willReturn('https://wolnosciowiec.net');

        $message->expects($this->once())
            ->method('getAllowMarkdown');

        $message->expects($this->once())
            ->method('getIcon');

        $messenger = $this->createMessenger();
        $messenger->send($message);
    }

    protected function createMessenger(): SlackMessenger
    {
        $messenger = $this->getMockBuilder(SlackMessenger::class);
        $messenger->enableOriginalConstructor();
        $messenger->setConstructorArgs([
            new Logger('test'),
            $this->createMock(\Twig_Environment::class)
        ]);
        $messenger->setMethods(['createClient']);

        return $messenger->getMock();
    }
}
