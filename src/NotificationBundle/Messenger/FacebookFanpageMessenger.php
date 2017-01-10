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

    /**
     * @param MessengerConfigurationProvider $configurationProvider
     */
    public function __construct(MessengerConfigurationProvider $configurationProvider)
    {
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
            $response = $this->client->get('/' . $this->wallId . '?fields=access_token');
            $response->decodeBody();
            $decoded = $response->getDecodedBody();

            if (!isset($decoded['access_token']) || empty($decoded['access_token'])) {
                throw new InvalidMessengerConfigurationException(
                    'FacebookFanPage: Cannot get the access_token from server. ' .
                    'Details: ' . json_encode($decoded));
            }

            $this->accessToken = $decoded['access_token'];
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