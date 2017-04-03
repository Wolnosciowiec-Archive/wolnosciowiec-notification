<?php declare(strict_types=1);

namespace NotificationBundle\Model\Entity\Message;

use NotificationBundle\Model\Entity\Base\AutoSerializable;
use NotificationBundle\Model\Entity\MessageInterface;

/**
 * @package NotificationBundle\Model\Entity
 */
class Message extends AutoSerializable implements MessageInterface
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
     * @var string $title
     */
    private $title = '';

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

            // don't rely on id when generating id
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

    /**
     * @param string $title
     * @return Message
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}