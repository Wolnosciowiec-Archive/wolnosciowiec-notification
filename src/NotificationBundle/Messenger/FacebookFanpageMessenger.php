<?php declare(strict_types=1);

namespace NotificationBundle\Messenger;

use Facebook\Facebook;
use NotificationBundle\Model\Entity\Exception\InvalidMessengerConfigurationException;
use NotificationBundle\Model\Entity\MessageInterface;

/**
 * @package NotificationBundle\Messenger
 */
class FacebookFanPageMessenger extends BaseMessenger implements MessengerInterface
{
    /** @var Facebook $client */
    private $client;

    /** @var string $wallId */
    private $wallId;

    /** @var string $accessToken */
    private $accessToken = '';

    public function reconfigure()
    {
        $this->client = new Facebook([
            'app_id'     => $this->getConfig()->get('app_id'),
            'app_secret' => $this->getConfig()->get('app_secret'),
        ]);

        $this->wallId = $this->getConfig()->get('wall_id');
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
                '\&client_id\=' . $this->getConfig()->get('app_id') .
                '\&client_secret\=' . $this->getConfig()->get('app_secret')));

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