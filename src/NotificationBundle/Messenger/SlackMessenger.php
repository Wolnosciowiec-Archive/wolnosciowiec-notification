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
    public function __construct(LoggerInterface $logger, \Twig_Environment $twig)
    {
        $this->logger = $logger;
        $this->twig   = $twig;
    }

    public function reconfigure()
    {
        $this->userName    = $this->getConfig()->get('user_name', 'Wolnosciowiec.net Notification');
        $this->channelName = $this->getConfig()->get('channel_name');
        $this->hookUrl     = $this->getConfig()->get('hook_url');
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

        $client = $this->createClient($this->getHookUrl($message), $settings);
        $slackMessage = $this->createMessage($client);
        $content = $this->renderMessage($message);

        // basic information (MessageInterface)
        $slackMessage->setUsername($this->userName);
        $slackMessage->setChannel($this->channelName);
        $slackMessage->setText($content ? $content : $message->getTitle());

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

    /**
     * @param Client $client
     * @return Message
     */
    protected function createMessage(Client $client): Message
    {
        return new Message($client);
    }

    /**
     * @param string $url
     * @param array $settings
     *
     * @return Client
     */
    protected function createClient(string $url, array $settings): Client
    {
        return new Client($url, $settings);
    }

    private function getHookUrl(MessageInterface $message)
    {
        if ($message instanceof SlackMessage && strlen($message->getHookUrl()) > 0) {
            return $message->getHookUrl();
        }

        return $this->hookUrl;
    }

    /**
     * @param MessageInterface $message
     * @return string
     */
    protected function renderMessage(MessageInterface $message)
    {
        $message = parent::renderMessage($message);
        return strip_tags($message);
    }
}
