<?php

namespace Tests\SocialShareBundle\Controller;

use NotificationBundle\Factory\Messenger\MessengerFactory;
use NotificationBundle\Messenger\MessengerInterface;
use Tests\SocialShareBundle\TestCase;

/**
 * @see MessengerFactory
 * @package Tests\SocialShareBundle\Controller
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