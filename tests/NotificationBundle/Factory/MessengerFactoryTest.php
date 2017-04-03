<?php

namespace Tests\NotificationBundle\Controller;

use NotificationBundle\Factory\Messenger\MessengerFactory;
use NotificationBundle\Messenger\MessengerInterface;
use Tests\NotificationBundle\TestCase;

/**
 * @see MessengerFactory
 * @package Tests\NotificationBundle\Controller
 */
class MessengerFactoryTest extends TestCase
{
    /**
     * @return MessengerFactory
     */
    private function getFactory()
    {
        return $this->getContainer()->get('notificationbundle.factory.messenger');
    }

    /**
     * @see MessengerFactory::getMessengers()
     */
    public function testGetMessengers()
    {
        $this->assertNotEmpty($this->getFactory()->getMessengers());

        foreach ($this->getFactory()->getMessengers() as $messenger) {
            $this->assertInstanceOf(MessengerInterface::class, $messenger);
        }
    }
}