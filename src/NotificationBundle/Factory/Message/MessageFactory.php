<?php

namespace NotificationBundle\Factory\Message;

use NotificationBundle\Model\Entity\Exception\IncompleteMessageParametersException;
use NotificationBundle\Model\Entity\Exception\InvalidMessageTypeException;
use NotificationBundle\Model\Entity\MessageInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Creates entities that contains message details
 * ----------------------------------------------
 *
 * @package NotificationBundle\Factory\Message
 */
class MessageFactory
{
    /** @var SerializerInterface $serializer */
    private $serializer;

    /** @var array $allowedClasses */
    private $allowedClasses = [];

    /** @var LoggerInterface $logger */
    private $logger;

    /**
     * @param SerializerInterface            $serializer
     * @param LoggerInterface                $logger
     */
    public function __construct(
        $serializer,
        LoggerInterface $logger)
    {
        $this->serializer    = $serializer;
        $this->logger        = $logger;
    }

    /**
     * Create an instance of a message entity
     * that is implementing the MessageInterface
     *
     * @param array $data
     * @param string $className
     *
     * @throws InvalidMessageTypeException
     * @throws IncompleteMessageParametersException
     *
     * @return MessageInterface
     */
    public function createMessage(array $data, $className)
    {
        if (!isset($this->allowedClasses[$className])) {
            $this->logger->error('Trying to use not allowed type "' . $className . '"');
            throw new InvalidMessageTypeException('Message type not listed in allowed types');
        }

        /** @var MessageInterface $message */
        $message = $this->serializer->deserialize(
            json_encode($data),
            $this->allowedClasses[$className],
            'json'
        );

        return $message;
    }

    /**
     * @param array $allowedClasses
     * @return MessageFactory
     */
    public function setAllowedClasses($allowedClasses)
    {
        $this->allowedClasses = $allowedClasses;
        return $this;
    }
}