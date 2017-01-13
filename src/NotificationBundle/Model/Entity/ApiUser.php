<?php

namespace NotificationBundle\Model\Entity;

use NotificationBundle\Security\Roles;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @codeCoverageIgnore
 * @package NotificationBundle\Model\Entity
 */
class ApiUser implements UserInterface
{
    /**
     * @param string $id
     */
    private $id;

    /**
     * @var string $username
     */
    private $username;

    /**
     * @var string $apiKey
     */
    private $apiKey;

    /**
     * @var \DateTime $dateAdded
     */
    private $dateAdded;

    /**
     * @var bool $active
     */
    private $active = true;

    public function __construct()
    {
        $this->dateAdded = new \DateTime();
    }

    /**
     * @param bool $active
     * @return $this
     */
    public function setActive(bool $active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return [
            Roles::ROLE_SEND_MESSAGES,
        ];
    }

    public function getPassword()
    {
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId(string $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $apiKey
     * @return ApiUser
     */
    public function setApiKey(string $apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @return \DateTime
     */
    public function getDateAdded(): \DateTime
    {
        return $this->dateAdded;
    }

    /**
     * @param \DateTime $dateAdded
     * @return ApiUser
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
        return $this;
    }

    /**
     * @param string $username
     * @return ApiUser
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }
}