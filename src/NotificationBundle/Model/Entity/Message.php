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
     * @var bool $couldBeTruncated
     */
    private $couldBeTruncated = true;

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
     * @inheritdoc
     */
    public function getCouldBeTruncated(): bool
    {
        return $this->couldBeTruncated;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize([
            'content'            => $this->content,
            'id'                 => $this->id,
            'group_name'         => $this->groupName,
            'could_be_truncated' => $this->couldBeTruncated,
        ]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->content          = $data['content']            ?? '';
        $this->id               = $data['id']                 ?? null;
        $this->groupName        = $data['group_name']         ?? null;
        $this->couldBeTruncated = $data['could_be_truncated'] ?? null;
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

    /**
     * @param boolean $couldBeTruncated
     * @return Message
     */
    public function setCouldBeTruncated($couldBeTruncated)
    {
        $this->couldBeTruncated = $couldBeTruncated;
        return $this;
    }
}