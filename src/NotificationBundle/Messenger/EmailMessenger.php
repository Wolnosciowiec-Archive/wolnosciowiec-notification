<?php

namespace NotificationBundle\Messenger;

use NotificationBundle\Model\Entity\Message\MailMessageInterface;
use NotificationBundle\Model\Entity\MessageInterface;
use NotificationBundle\Model\Entity\WithRendererInterface;
use Psr\Log\LoggerInterface;

/**
 * @package NotificationBundle\Factory\Messenger
 */
class EmailMessenger extends BaseMessenger implements MessengerInterface
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
     * @var LoggerInterface $logger
     */
    private $logger;

    /**
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     * @param LoggerInterface $logger
     */
    public function __construct(
        \Swift_Mailer     $mailer,
        \Twig_Environment $twig,
        LoggerInterface   $logger
    ) {
        $this->mailer = $mailer;
        $this->twig   = $twig;
        $this->logger = $logger;
    }

    public function reconfigure()
    {
    }

    /**
     * @return string
     */
    private function getFromAddress(): string
    {
        return $this->getConfig()->get('default_from');
    }

    /**
     * @param MailMessageInterface $message
     * @return array
     */
    private function getRecipients(MailMessageInterface $message) : array
    {
        if (empty($message->getRecipients())) {
            return $this->getConfig()->get('default_recipients', []);
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

        return $message->getContent();
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
