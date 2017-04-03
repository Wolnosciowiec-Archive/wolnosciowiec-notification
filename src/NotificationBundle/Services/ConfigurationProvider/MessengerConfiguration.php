<?php declare(strict_types=1);

namespace NotificationBundle\Services\ConfigurationProvider;

use NotificationBundle\Model\Entity\Exception\InvalidMessengerConfigurationException;
use NotificationBundle\Model\Entity\MessageInterface;

class MessengerConfiguration
{
    private $group         = [];
    private $configuration = [];

    public function __construct(array $group)
    {
        $this->group         = $group;
        $this->configuration = $group['config'];
        $this->validateGroupConfiguration($group);
    }

    private function validateGroupConfiguration(array $group)
    {
        if (!isset($group['class'])
            || !isset($group['groups'])
            || !isset($group['service_id'])
            || !isset($group['config'])
            || !class_exists($group['class'])
        ) {

            throw new InvalidMessengerConfigurationException(
                $group['class'] . ': Invalid configuration in section notification/enabled_messengers.' .
                ' Missing a valid class, config, service_id or groups. Got "' . json_encode($group) . '"');
        }

        if (!is_array($group['config'])) {
            throw new InvalidMessengerConfigurationException($group['class'] . ': The config key should be an array');
        }
    }

    public function get(string $key, $defaultValue = null, $useDefaultValue = true)
    {
        if (!isset($this->configuration[$key])) {

            if ($useDefaultValue === false) {
                throw new InvalidMessengerConfigurationException('Key "' . $key . '" not defined');
            }

            return $defaultValue;
        }

        return $this->configuration[$key];
    }

    /**
     * Check by group name if the specific message should be sent using this messenger
     * (configuration section: notification/enabled_messengers)
     *
     * Example:
     *     enabled_messengers:
     *          "notificationbundle.messenger.twitter":
     *              class: "NotificationBundle\\Messenger\\TwitterMessenger"
     *              groups:
     *                  - portal_content_update_notification
     *
     * @param MessageInterface $message
     * @return bool
     */
    public function isGroupAllowedForMessenger(MessageInterface $message)
    {
        return in_array($message->getGroupName(), $this->group['groups']);
    }
}
