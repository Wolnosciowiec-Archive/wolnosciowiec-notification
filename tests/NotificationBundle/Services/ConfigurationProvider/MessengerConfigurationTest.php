<?php declare(strict_types=1);

namespace Tests\NotificationBundle\Services\ConfigurationProvider;

use NotificationBundle\Messenger\EmailMessenger;
use NotificationBundle\Model\Entity\Exception\InvalidMessengerConfigurationException;
use NotificationBundle\Model\Entity\Message\Message;
use NotificationBundle\Services\ConfigurationProvider\MessengerConfiguration;
use Tests\NotificationBundle\TestCase;

/**
 * @see MessengerConfiguration
 */
class MessengerConfigurationTest extends TestCase
{
    private function provideValidExample(): MessengerConfiguration
    {
        return new MessengerConfiguration([
            'service_id' => 'notificationbundle.messenger.email',
            'class'  => EmailMessenger::class,
            'groups' => [
                'email', 'failure_notification', 'export_generation_result',
            ],
            'config' => [
                'default_from' => 'test@wolnosciowiec.net',
                'default_recipients' => ['test+recipient@wolnosciowiec.net'],
            ]
        ]);
    }

    private function provideMissingSectionExample(): MessengerConfiguration
    {
        return new MessengerConfiguration([
            'service_id' => 'notificationbundle.messenger.slack',
            'groups' => [
                'slack',
            ],
            'config' => [
                'api_token' => '...',
            ]
        ]);
    }

    private function provideInvalidConfigExample(): MessengerConfiguration
    {
        return new MessengerConfiguration([
            'service_id' => 'notificationbundle.messenger.email',
            'class'  => EmailMessenger::class,
            'groups' => [
                'email',
            ],
            'config' => 'test',
        ]);
    }

    /**
     * @see MessengerConfiguration::isGroupAllowedForMessenger()
     */
    public function testIsGroupAllowedForMessenger()
    {
        $configuration = $this->provideValidExample();
        $message = new Message('this is a test');

        $this->assertTrue($configuration->isGroupAllowedForMessenger($message->setGroupName('failure_notification')));
        $this->assertFalse($configuration->isGroupAllowedForMessenger($message->setGroupName('invalid-group-name-here')));
    }

    /**
     * @see MessengerConfiguration::get()
     */
    public function testGet()
    {
        $configuration = $this->provideValidExample();

        // array
        $this->assertSame(['test+recipient@wolnosciowiec.net'], $configuration->get('default_recipients'));

        // key that does not exists but have a default
        $this->assertSame('default value', $configuration->get('test_this_key_does_not_exists', 'default value'));
    }

    /**
     * @see MessengerConfiguration::get()
     */
    public function testFailureGet()
    {
        $this->expectException(InvalidMessengerConfigurationException::class);

        $configuration = $this->provideValidExample();
        $configuration->get('non_existing_key_without_a_default_should_raise_an_exception', null, false);
    }

    /**
     * @see MessengerConfiguration::validateGroupConfiguration()
     */
    public function testInvalidConfigValidateGroupConfiguration()
    {
        $this->expectException(InvalidMessengerConfigurationException::class);
        $this->provideInvalidConfigExample();
    }

    /**
     * @see MessengerConfiguration::validateGroupConfiguration()
     */
    public function testMissingSectionValidateGroupConfiguration()
    {
        $this->expectException(InvalidMessengerConfigurationException::class);
        $this->provideMissingSectionExample();
    }
}
