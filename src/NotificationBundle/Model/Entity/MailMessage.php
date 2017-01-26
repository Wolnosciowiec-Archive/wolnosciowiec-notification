<?php declare(strict_types=1);

namespace NotificationBundle\Model\Entity;

/**
 * @package NotificationBundle\Model\Entity
 */
class MailMessage extends Message implements MailMessageInterface
{
    /** @var string $subject */
    private $subject;

    /** @var string $from */
    private $from = '';

    /** @var array $recipients */
    private $recipients = [];

    /**
     * @return string
     */
    public function getSubject() : string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return MailMessage
     */
    public function setSubject($subject) : MailMessage
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @param string $from
     * @return MailMessage
     */
    public function setFrom($from) : MailMessage
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return string
     */
    public function getFrom() : string
    {
        return $this->from;
    }

    /**
     * @param array $recipients
     * @return MailMessage
     */
    public function setRecipients($recipients) : MailMessage
    {
        $this->recipients = $recipients;
        return $this;
    }

    /**
     * @return array
     */
    public function getRecipients() : array
    {
        return $this->recipients;
    }
}