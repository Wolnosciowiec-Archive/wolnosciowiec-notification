<?php declare(strict_types=1);

namespace NotificationBundle\Messenger;

use Maknz\Slack\Client;
use Maknz\Slack\Message;
use NotificationBundle\Model\Entity\MessageInterface;
use NotificationBundle\Model\Entity\Message\SlackMessage;
use Psr\Log\LoggerInterface;

class SlackMessenger extends BaseMessenger implements MessengerInterface
{
    /** @var LoggerInterface $logger */
    protected $logger;

    /** @var string $channelName */
    protected $channelName;

    /** @var string $userName */
    protected $userName;

    /** @var string $hookUrl */
    protected $hookUrl;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger      = $logger;
    }

    public function reconfigure()
    {
        $this->userName    = $this->getConfig()->get('user_name', 'Wolnosciowiec.net Notification');
        $this->channelName = $this->getConfig()->get('channel_name');
        $this->hookUrl     = $this->getConfig()->get('hook_url');
    }

    private function getHookUrl(MessageInterface $message)
    {
        if ($message instanceof SlackMessage && strlen($message->getHookUrl()) > 0) {
            return $message->getHookUrl();
        }

        return $this->hookUrl;
    }

    /**
     * @inheritdoc
     */
    public function send(MessageInterface $message): bool
    {
        $settings = [
            'username' => $this->userName,
            'channel' => $this->channelName,
        ];

        $client = new Client($this->getHookUrl($message), $settings);
        $slackMessage = new Message($client);

        // basic information (MessageInterface)
        $slackMessage->setUsername($this->userName);
        $slackMessage->setChannel($this->channelName);
        $slackMessage->setText($message->getContent());

        // extended information (dedicated SlackMessage format)
        if ($message instanceof SlackMessage) {
            $slackMessage->setAllowMarkdown($message->getAllowMarkdown());
            $slackMessage->setIcon($message->getIcon());

            if ($message->getChannelName()) {
                $slackMessage->setChannel($message->getChannelName());
            }

            if ($message->getUsername()) {
                $slackMessage->setUsername($message->getUsername());
            }
        }

        $client->sendMessage($slackMessage);

        // shitty maknz/slack library does not return responses
        return true;
    }
}
