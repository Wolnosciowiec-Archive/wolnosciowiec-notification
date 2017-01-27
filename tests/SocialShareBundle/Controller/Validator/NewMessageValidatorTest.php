<?php declare(strict_types=1);

use NotificationBundle\Controller\Validator\NewMessageValidator;
use Tests\SocialShareBundle\TestCase;

/**
 * @see NewMessageValidator
 */
class NewMessageValidatorTest extends TestCase
{
    public function provideConditions()
    {
        return [
            'Missing message type in the request' => [
                [
                    'content'    => 'News: Postmans are angry, they want a salary increase',
                    'group_name' => 'new_post',
                ],
                '',
                false,
                'missing_message_type',
                '',
            ],

            'The JSON payload is missing' => [
                [],
                'Message',
                false,
                'missing_input_array_data',
                '',
            ],

            'Message type not supported' => [
                [
                    'content'    => 'News: CNT-AIT congress in Warsaw',
                    'group_name' => 'new_post',
                ],
                'DamnThisMessageTypeIsInvalid',
                false,
                'invalid_message_type',
                '',
            ],

            'Message group not supported' => [
                [
                    'group_name' => 'new_post',
                ],
                'Message',
                false,
                'invalid_group_name',
                'Message group not supported',
            ],

            'Missing basic parameters' => [
                [
                    'content' => null,
                ],
                'Message',
                false,
                'missing_input_parameters',
                'Missing "group_name" or "content" fields. Check documentation for input "message_type"',
            ],

            'Missing fields in Email message (uses Symfony Validator)' => [
                [
                    'group_name'       => 'admin_alert',
                    'exceptionMessage' => 'T',
                ],
                'FailureNotification',
                false,
                'validation_failed',
                'service_name_required, request_information_required, exception_message_required',
            ]
        ];
    }

    /**
     * @see NewMessageValidator::validate()
     * @dataProvider provideConditions
     *
     * @param array  $data
     * @param string $messageType
     * @param bool   $expectedState
     * @param string $expectedReason
     * @param string $expectedMessage
     */
    public function testValidate(
        array $data,
        string $messageType,
        bool $expectedState,
        string $expectedReason,
        string $expectedMessage
    )
    {
        $result = $this->getValidator()->validate($data, $messageType);

        $this->assertSame($expectedState,   $result->isSuccess());
        $this->assertSame($expectedMessage, $result->getMessage());
        $this->assertSame($expectedReason,  $result->getReason());
    }

    /**
     * @return NewMessageValidator
     */
    private function getValidator()
    {
        return $this->getContainer()->get('notificationbundle.validator.message');
    }
}