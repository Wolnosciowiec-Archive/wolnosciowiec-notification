<?php

namespace NotificationBundle\Queue;

use NotificationBundle\Model\Entity\Exception\QueueException;
use NotificationBundle\Model\Entity\MessageInterface;
use NotificationBundle\Services\ConfigurationProvider\QueueConfigurationProvider;

/**
 * Queue implementation in Redis as a storage
 * ------------------------------------------
 *
 * @package NotificationBundle\Queue
 */
class RedisQueue implements QueueInterface
{
    /**
     * @var \Predis\Client $client
     */
    private $client;

    public function __construct(QueueConfigurationProvider $configurationProvider)
    {
        $this->client = new \Predis\Client($configurationProvider->getAll());
    }

    /**
     * @param MessageInterface $message
     * @return string
     */
    private function encode(MessageInterface $message)
    {
        return json_encode(
            [
                'class' => get_class($message),
                'data'  => $message->serialize(),
            ]
        );
    }

    /**
     * @param string $encoded
     * @throws \Exception
     * @return MessageInterface
     */
    private function decode($encoded)
    {
        $array = json_decode($encoded, true);

        if (!is_array($array)
            || !isset($array['class'])
            || !isset($array['data'])) {
            throw new QueueException('Decode failed, object was not properly saved or was truncated');
        }

        /** @var MessageInterface $object */
        $object = new $array['class']();
        $object->unserialize($array['data']);

        return $object;
    }

    /**
     * @return string
     */
    private function getPrefix()
    {
        return 'SocialShare:Queue|';
    }

    /**
     * @inheritdoc
     */
    public function push(MessageInterface $message)
    {
        $this->client->set($this->getPrefix() . $message->getId(), $this->encode($message));
    }

    /**
     * @inheritdoc
     */
    public function findAll()
    {
        $items = $this->client->keys($this->getPrefix() . '*');

        return array_map(function ($item) {

            try {
                return $this->decode($this->client->get($item));
            }
            catch (QueueException $e) {
                return null;
            }

        }, $items);
    }

    /**
     * @inheritdoc
     */
    public function popOut(MessageInterface $message)
    {
        $this->popOutById($message->getId());
    }

    /**
     * @inheritdoc
     */
    public function popOutById(string $id)
    {
        $this->client->del([$this->getPrefix() . $id]);
    }
}
