<?php

namespace NotificationBundle\Security;

use Doctrine\ORM\EntityManager;
use NotificationBundle\Model\Entity\ApiUser;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Fetches users for authentication by token id
 * --------------------------------------------
 *
 * @package NotificationBundle\Security
 */
class UserProvider implements UserProviderInterface
{
    /** @var EntityManager $em */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @inheritdoc
     */
    public function loadUserByUsername($apiKey)
    {
        $user = $this->em->getRepository(ApiUser::class)
            ->findOneBy(
                [
                    'apiKey' => $apiKey,
                    'active' => true,
                ]
            );

        if (!$user instanceof UserInterface) {
            throw new UsernameNotFoundException(
                sprintf('API key is no longer valid', $apiKey)
            );
        }

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @inheritdoc
     */
    public function supportsClass($class)
    {
        return ApiUser::class === $class;
    }
}