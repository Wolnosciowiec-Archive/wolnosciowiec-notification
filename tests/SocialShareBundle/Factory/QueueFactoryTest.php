<?php

namespace Tests\SocialShareBundle\Controller;

use NotificationBundle\Factory\Queue\QueueFactory;
use NotificationBundle\Queue\QueueInterface;
use Tests\SocialShareBundle\TestCase;

/**
 * @see QueueFactory
 * @package Tests\SocialShareBundle\Controller
 */
class QueueFactoryTest extends TestCase
{
    /**
     * @return QueueFactory
     */
    private function getFactory()
    {
        return $this->getContainer()->get('notificationbundle.factory.queue');
    }

    /**
     * @see MessengerFactory::getMessengers()
     */
    public function testGetMessengers()
    {
        $this->assertInstanceOf(QueueInterface::class, $this->getFactory()->getQueue());
    }
}