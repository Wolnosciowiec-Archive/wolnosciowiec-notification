<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use NotificationBundle\Model\Entity\ApiUser;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Creates a user
 */
class Version20170110112639 extends AbstractMigration implements ContainerAwareInterface
{
    /** @var ContainerInterface $container */
    private $container;

    /**
     * @param ContainerInterface $container
     * @return $this
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * @return string
     */
    private function createApiKey(): string
    {
        $defaultKey = $this->container->getParameter('default_api_key');

        if (strlen($defaultKey) > 0 && $defaultKey !== '~') {
            return $defaultKey;
        }

        return substr(
            str_shuffle(sha1(rand(99, 99999) . time())),
            0, 32
        );
    }

    /**
     * @return string
     */
    private function createUserName(): string
    {
        $defaultUserName = $this->container->getParameter('default_api_user');

        if (!empty($defaultUserName)) {
            return $defaultUserName;
        }

        return 'app';
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $apiUser = new ApiUser();
        $apiUser->setUsername($this->createUserName());
        $apiUser->setActive(true);
        $apiUser->setApiKey($this->createApiKey());
        $apiUser->setDateAdded(new \DateTime());

        $this->write(' ==> Account name: <info>' . $apiUser->getUsername() . '</info>');
        $this->write(' ==> Your API key is <info>' . $apiUser->getApiKey() . '</info>');

        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($apiUser);
        $em->flush($apiUser);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $user = $em->getRepository(ApiUser::class)
            ->findOneBy(['username' => 'app']);

        $em->remove($user);
        $em->flush($user);
    }
}
