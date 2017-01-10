<?php

namespace Tests\SocialShareBundle\Controller;

use Tests\SocialShareBundle\TestCase;

/**
 * @see QueueMessageController
 * @package Tests\SocialShareBundle\Controller
 */
class QueueMessageControllerTest extends TestCase
{
    /**
     * @return array
     */
    public function provideMessageData()
    {
        return [
            'valid message and type, without valid token' => [
                [
                    'content'    => 'Hello from test',
                    'group_name' => 'example_1',
                ],
                'Message',
                [
                    'Authentication Required',
                ],
                []
            ],

            'valid message and type, with valid token' => [
                [
                    'content'    => 'Valid request',
                    'group_name' => 'example_1',
                ],
                'Message',
                [
                    'Sent to queue',
                ],
                [
                    'HTTP_X_AUTH_TOKEN' => $this->getTestUser()->getApiKey(),
                    'CONTENT_TYPE' => 'application/json'
                ]
            ],

            'invalid message type' => [
                [
                    'content'    => 'Invalid request',
                    'group_name' => 'example_1',
                ],
                'INVALID',
                [
                    'invalid_message_type',
                ],
                [
                    'HTTP_X_AUTH_TOKEN' => $this->getTestUser()->getApiKey(),
                    'CONTENT_TYPE' => 'application/json'
                ]
            ],

            'missing message type' => [
                [
                    'content'    => 'Invalid request',
                    'group_name' => 'example_1',
                ],
                '',
                [
                    'missing_message_type',
                ],
                [
                    'HTTP_X_AUTH_TOKEN' => $this->getTestUser()->getApiKey(),
                    'CONTENT_TYPE' => 'application/json'
                ]
            ],

            'missing basic arguments' => [
                [
                ],
                'Message',
                [
                    'missing_input_array_data',
                ],
                [
                    'HTTP_X_AUTH_TOKEN' => $this->getTestUser()->getApiKey(),
                    'CONTENT_TYPE' => 'application/json'
                ]
            ],
        ];
    }

    /**
     * @dataProvider provideMessageData
     *
     * @param array  $messageData
     * @param string $messageType
     * @param array  $responseContains
     * @param array  $headers
     */
    public function testAddAction(array $messageData, string $messageType, array $responseContains, array $headers)
    {
        $client = static::createClient();
        $client->request(
            'PUT',
            '/message/queue/add?message_type=' . $messageType,
            [], [],
            $headers,
            json_encode($messageData)
        );

        foreach ($responseContains as $string) {
            $this->assertContains($string, $client->getResponse()->getContent());
        }
    }
}
