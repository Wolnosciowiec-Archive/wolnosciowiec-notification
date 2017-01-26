<?php

namespace NotificationBundle\Queue;

use JMS\Serializer\Exception\RuntimeException;
use JMS\Serializer\SerializerInterface;
use NotificationBundle\Model\Entity\Exception\QueueException;
use NotificationBundle\Model\Entity\MessageInterface;
use NotificationBundle\Services\ConfigurationProvider\QueueConfigurationProvider;
use Psr\Log\LoggerInterface;

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

    /**
     * @var SerializerInterface $serializer
     */
    private $serializer;

    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    public function __construct(
        QueueConfigurationProvider $configurationProvider,
        SerializerInterface $serializer,
        LoggerInterface $logger
    ) {
        $this->client     = new \Predis\Client($configurationProvider->getAll());
        $this->serializer = $serializer;
        $this->logger     = $logger;
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
                'data'  => base64_encode($this->serializer->serialize($message, 'json')),
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

        try {
            return $this->serializer->deserialize(base64_decode($array['data']), $array['class'], 'json');

        } catch (RuntimeException $e) {
            $this->logger->error('Deserialization failed: ' . $e->getMessage());
            throw new QueueException('Decode failed, probably a malformed data was put into queue, or upgrade from older version was performed', 0, $e);
        }
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
        return true;
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
                $this->logger->error('Got error while decoding item. ' . $e->getMessage() . ', content: ' . $item);
                $this->popOutById($item);
                return null;
            }

        }, $items);
    }

    /**
     * @inheritdoc
     */
    public function popOut(MessageInterface $message)
    {
        return $this->popOutById($message->getId());
    }

    /**
     * @inheritdoc
     */
    public function popOutById(string $id)
    {
        $this->client->del([$this->getPrefix() . str_replace($this->getPrefix(), '', $id)]);
        return true;
    }
}
