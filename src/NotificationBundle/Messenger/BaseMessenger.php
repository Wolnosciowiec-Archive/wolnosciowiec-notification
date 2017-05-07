<?php declare(strict_types=1);

namespace NotificationBundle\Messenger;

use NotificationBundle\Model\Entity\MessageInterface;
use NotificationBundle\Model\Entity\WithRendererInterface;
use NotificationBundle\Services\ConfigurationProvider\MessengerConfiguration;

abstract class BaseMessenger implements MessengerInterface
{
    /**
     * @var string $_configuration
     */
    private $_configuration = '';

    /**
     * @var \Twig_Environment $twig
     */
    protected $twig;

    public function setConfiguration(MessengerConfiguration $configuration): MessengerInterface
    {
        // be immutable
        if (!empty($this->_configuration)) {
            throw new \LogicException('The configuration should be set only once, don\'t try to do any tricks');
        }

        $this->_configuration = $configuration;
        $this->reconfigure();

        return $this;
    }

    public function getConfig(): MessengerConfiguration
    {
        return $this->_configuration;
    }

    /**
     * Render the message using Twig if the message
     * implements WithRendererInterface
     *
     * @param MessageInterface $message
     * @return string
     */
    protected function renderMessage(MessageInterface $message)
    {
        if ($message instanceof WithRendererInterface
            && $this->twig instanceof \Twig_Environment) {
            return $this->twig->render(
                $message->getTemplateName(),
                ['message' => $message]
            );
        }

        return $message->getContent();
    }
}
