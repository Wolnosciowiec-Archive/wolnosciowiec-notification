<?php

namespace NotificationBundle\Messenger;

use Facebook\Facebook;
use NotificationBundle\Model\Entity\Exception\InvalidMessengerConfigurationException;
use NotificationBundle\Model\Entity\MessageInterface;
use NotificationBundle\Services\ConfigurationProvider\MessengerConfigurationProvider;

/**
 * @package NotificationBundle\Messenger
 */
class FacebookFanPageMessenger implements MessengerInterface
{
    /** @var Facebook $client */
    private $client;

    /** @var string $wallId */
    private $wallId;

    /** @var string $accessToken */
    private $accessToken = '';

    /** @var MessengerConfigurationProvider $conf */
    private $conf;

    /**
     * @param MessengerConfigurationProvider $configurationProvider
     */
    public function __construct(MessengerConfigurationProvider $configurationProvider)
    {
        $this->conf   = $configurationProvider;
        $this->client = new Facebook([
            'app_id'     => $configurationProvider->get('app_id', 'facebook'),
            'app_secret' => $configurationProvider->get('app_secret', 'facebook'),
        ]);

        $this->wallId = $configurationProvider->get('wall_id', 'facebook');
    }

    /**
     * @throws InvalidMessengerConfigurationException
     * @return string
     */
    private function getAccessToken(): string
    {
        if (strlen($this->accessToken) === 0) {

            $token = explode('access_token=', shell_exec(
                'curl -s  https://graph.facebook.com/v2.2/oauth/access_token\?grant_type\=client_credentials' .
                '\&redirect_uri=https://wolnosciowiec.net/oauth/fb' .
                '\&client_id\=' . $this->conf->get('app_id', 'facebook') .
                '\&client_secret\=' . $this->conf->get('app_secret', 'facebook')));

            $this->accessToken = $token[1];
        }

        return $this->accessToken;
    }

    /**
     * @param MessageInterface $message
     * @return bool
     */
    public function send(MessageInterface $message): bool
    {
        $this->client->post(
            '/' . $this->wallId . '/feed',
            ['message' => $message->getContent()],
            $this->getAccessToken()
        );

        return true;
    }
}