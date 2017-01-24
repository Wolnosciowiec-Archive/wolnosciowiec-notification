<?php

namespace NotificationBundle\Messenger;

use NotificationBundle\Model\Entity\MailMessageInterface;
use NotificationBundle\Model\Entity\MessageInterface;
use NotificationBundle\Model\Entity\WithRendererInterface;

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
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig   = $twig;
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
            return false;
        }

        $mailMessage = new \Swift_Message(
            $this->renderMessage($message),
            $message->getContent()
        );

        $mailMessage->setTo($message->getRecipients());

        return $this->mailer->send($mailMessage) > 0;
    }
}