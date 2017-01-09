<?php

namespace NotificationBundle\Services\ConfigurationProvider;
use NotificationBundle\Messenger\MessengerInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * Stores information about messengers eg. token ids, passwords
 *
 * @package NotificationBundle\Services
 */
class MessengerConfigurationProvider extends AbstractConfigurationProvider
{
    /**
     * @var array $messengerGroupsByClass
     */
    private $messengerGroupsByClass = [];

    /**
     * @param array $messengersConfigs
     * @param array $enabledGroups
     */
    public function setMessengersConfiguration($messengersConfigs, $enabledGroups)
    {
        foreach ($enabledGroups as $serviceId => $group) {

            if (!isset($group['class'])
                || !isset($group['groups'])
                || !class_exists($group['class'])) {

                throw new InvalidConfigurationException(
                    $serviceId . ': Invalid configuration in section notification/enabled_messengers.' .
                    ' Missing a valid class or list of groups');
            }

            $this->messengerGroupsByClass[$group['class']] = $group['groups'];
        }

        $this->setAllConfiguration($messengersConfigs);
    }

    /**
     * List of all messenger groups where it is enabled
     * (configuration section: notification/enabled_messengers)
     *
     * Example:
     *     enabled_messengers:
     *          "notificationbundle.messenger.twitter":
     *              class: "NotificationBundle\\Messenger\\TwitterMessenger"
     *              groups:
     *                  - portal_content_update_notification
     *
     * @param MessengerInterface $messenger
     *
     * @throws \InvalidArgumentException
     * @return array
     */
    public function getMessengerAllowedGroups(MessengerInterface $messenger)
    {
        $messengerClassName = get_class($messenger);

        if (!isset($this->messengerGroupsByClass[$messengerClassName])) {
            throw new \InvalidArgumentException(
                '"' . $messengerClassName . '" is not defined in notification/enabled_messengers' .
                ' but called in code');
        }

        return $this->messengerGroupsByClass[$messengerClassName];
    }

    /**
     * Does messenger have defined selected group?
     *
     * @param string $groupName
     * @param MessengerInterface $messenger
     * @return bool
     */
    public function isGroupAllowedForMessenger($groupName, $messenger)
    {
        return in_array($groupName, $this->getMessengerAllowedGroups($messenger));
    }

    /**
     * Lists all messenger groups indexed by class name
     *
     * @return array
     */
    public function getMessengerGroupsByClass()
    {
        return $this->messengerGroupsByClass;
    }
}