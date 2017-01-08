<?php

namespace NotificationBundle\Model\Entity;

/**
 * @package NotificationBundle\Model\Entity
 */
class Message implements MessageInterface
{
    /**
     * @var string $content
     */
    private $content = '';

    /**
     * @var null|string $id
     */
    private $id = null;

    /**
     * @var string|null $groupName
     */
    private $groupName = null;

    /**
     * @param string $content
     */
    public function __construct(string $content = '')
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getGroupName()
    {
        return $this->groupName;
    }

    /**
     * @inheritdoc
     */
    public function getId(): string
    {
        if (null === $this->id) {
            $data = $this->serialize();

            if (isset($data['id'])) {
                unset($data['id']);
            }

            return md5($data);
        }

        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize([
            'content'   => $this->content,
            'id'        => $this->id,
            'group_name' => $this->groupName,
        ]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->content = $data['content'] ?? '';
        $this->id      = $data['id'] ?? null;
        $this->groupName = $data['group_name'] ?? null;
    }

    /**
     * @param string $groupName
     * @return Message
     */
    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;
        return $this;
    }
}