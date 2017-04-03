<?php declare(strict_types=1);

namespace NotificationBundle\Model\Entity\Message;

use NotificationBundle\Model\Entity\Message\Message;

class SlackMessage extends Message
{
    /**
     * @var string $hookUrl
     */
    protected $hookUrl = '';

    /**
     * @var string $icon
     */
    protected $icon = '';

    /**
     * @var string $channelName
     */
    protected $channelName = '';

    /**
     * @var string $username
     */
    protected $username = '';

    /**
     * @var bool $allowMarkdown
     */
    protected $allowMarkdown = true;

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return SlackMessage
     */
    public function setIcon(string $icon)
    {
        $this->icon = $icon;
        return $this;
    }


    /**
     * @return string
     */
    public function getChannelName(): string
    {
        return $this->channelName;
    }

    /**
     * @param string $channelName
     * @return SlackMessage
     */
    public function setChannelName($channelName): SlackMessage
    {
        $this->channelName = $channelName;
        return $this;
    }

    /**
     * @param string $username
     * @return SlackMessage
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param bool $allowMarkdown
     * @return SlackMessage
     */
    public function setAllowMarkdown(bool $allowMarkdown)
    {
        $this->allowMarkdown = $allowMarkdown;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getAllowMarkdown(): bool
    {
        return $this->allowMarkdown;
    }

    /**
     * @param string $hookUrl
     * @return SlackMessage
     */
    public function setHookUrl(string $hookUrl)
    {
        $this->hookUrl = $hookUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getHookUrl(): string
    {
        return $this->hookUrl;
    }
}
