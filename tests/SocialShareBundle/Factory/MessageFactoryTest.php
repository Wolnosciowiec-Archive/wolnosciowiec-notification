<?php

namespace Tests\SocialShareBundle\Controller;

use NotificationBundle\Model\Entity\MessageInterface;
use Tests\SocialShareBundle\TestCase;

/**
 * @see MessageFactory
 * @package Tests\SocialShareBundle\Controller
 */
class MessageFactoryTest extends TestCase
{
    private function getFactory()
    {
        return $this->getContainer()->get('notificationbundle.factory.message');
    }

    /**
     * @return array
     */
    public function provideTestMessages()
    {
        return [
            'valid' => [
                [
                    'content'    => 'This is a test',
                    'group_name' => 'test',
                ],
                'Message',
                null,
            ],

            'invalid, invalid type name' => [
                [
                    'content'    => 'This is a test',
                    'group_name' => 'test',
                ],
                'Invalid',
                'Message type not listed in allowed types',
            ],

            'invalid, missing type' => [
                [
                    'content'    => 'This is a test',
                ],
                '',
                'Message type not listed in allowed types',
            ],
        ];
    }

    /**
     * @dataProvider provideTestMessages
     * @see MessageFactory::createMessage()
     *
     * @param array  $messageData
     * @param string $type
     */
    public function testMessageCreation(array $messageData, string $type, $exceptionMessage = null)
    {
        try {
            $this->assertInstanceOf(MessageInterface::class, $this->getFactory()->createMessage($messageData, $type));
        }
        catch (\Exception $e) {
            $this->assertSame($exceptionMessage, $e->getMessage());
        }
    }
}