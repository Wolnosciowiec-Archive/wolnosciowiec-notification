<?php

namespace Tests\NotificationBundle;

use NotificationBundle\Model\Entity\ApiUser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for tests, provides access to the container
 * ------------------------------------------------------
 *
 * @package Tests\NotificationBundle
 */
abstract class TestCase extends WebTestCase
{
    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        if (static::$kernel === null
            || static::$kernel->getContainer() === null) {
            static::bootKernel();
        }

        return static::$kernel->getContainer();
    }

    /**
     * @return ApiUser
     */
    public function getTestUser()
    {
        $em   = $this->getContainer()->get('doctrine.orm.entity_manager');
        $user = $em
            ->getRepository(ApiUser::class)
            ->findOneBy(['username' => 'phpunit_test']);

        if (!$user instanceof ApiUser) {
            $user = new ApiUser();
            $user->setUsername('phpunit_test');
            $user->setApiKey(str_shuffle(sha1(rand(99, 9999999) . microtime(true))));

            $em->persist($user);
            $em->flush($user);
        }

        return $user;
    }
}