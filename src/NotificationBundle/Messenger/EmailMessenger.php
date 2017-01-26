<?php

namespace NotificationBundle\Messenger;

use NotificationBundle\Model\Entity\MailMessageInterface;
use NotificationBundle\Model\Entity\MessageInterface;
use NotificationBundle\Model\Entity\WithRendererInterface;
use NotificationBundle\Services\ConfigurationProvider\MessengerConfigurationProvider;
use Psr\Log\LoggerInterface;

/**
 * @package NotificationBundle\Factory\Messenger
 */
class EmailMessenger implements MessengerInterface
{
    /**
     * @var \Swift_Mailer $mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment $twig
     */
    private $twig;

    /**
     * @var MessengerConfigurationProvider $configurationProvider
     */
    private $config;

    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    /**
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     * @param LoggerInterface $logger
     * @param MessengerConfigurationProvider $configurationProvider
     */
    public function __construct(
        \Swift_Mailer     $mailer,
        \Twig_Environment $twig,
        LoggerInterface   $logger,
        MessengerConfigurationProvider $configurationProvider
    ) {
        $this->mailer = $mailer;
        $this->twig   = $twig;
        $this->logger = $logger;
        $this->config = $configurationProvider;
    }

    /**
     * @return string
     */
    private function getFromAddress(): string
    {
        return $this->config->get('default_from', 'email');
    }

    /**
     * @param MailMessageInterface $message
     * @return array
     */
    private function getRecipients(MailMessageInterface $message) : array
    {
        if (empty($message->getRecipients())) {
            return $this->config->get('default_recipients', 'email') ?? [];
        }

        return $message->getRecipients() ?? [];
    }

    /**
     * Render the message using Twig if the message
     * implements WithRendererInterface
     *
     * @param MailMessageInterface $message
     * @return string
     */
    private function renderMessage(MailMessageInterface $message)
    {
        if ($message instanceof WithRendererInterface) {
            return $this->twig->render(
                $message->getTemplateName(),
                ['message' => $message]
            );
        }

        return $message->getSubject();
    }

    /**
     * @param MessageInterface $message
     * @return bool
     */
    public function send(MessageInterface $message): bool
    {
        if (!$message instanceof MailMessageInterface) {
            $this->logger->debug('Message is not a mail, not handling');
            return false;
        }

        if (empty($this->getRecipients($message))) {
            $this->logger->notice('Missing recipients, cannot send');
            return false;
        }

        $messageBody = $this->renderMessage($message);

        $mailMessage = new \Swift_Message(
            substr($message->getSubject(), 0, 64),
            $messageBody
        );

        $mailMessage->setContentType('text/html');
        $mailMessage->setFrom($this->getFromAddress());
        $mailMessage->setTo($this->getRecipients($message));

        // logging
        $this->logger->debug('Sending from ' .
            json_encode($mailMessage->getFrom()) .
            ' to ' .
            json_encode($mailMessage->getTo())
        );

        return $this->mailer->send($mailMessage) > 0;
    }
}
