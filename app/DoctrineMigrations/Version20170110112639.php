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
    private function generateRandomKey()
    {
        return substr(
            str_shuffle(sha1(rand(99, 99999) . time())),
            0, 32
        );
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $apiUser = new ApiUser();
        $apiUser->setUsername('app');
        $apiUser->setActive(true);
        $apiUser->setApiKey($this->generateRandomKey());
        $apiUser->setDateAdded(new \DateTime());

        $this->write(' ==> Account name: <info>app</info>');
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
