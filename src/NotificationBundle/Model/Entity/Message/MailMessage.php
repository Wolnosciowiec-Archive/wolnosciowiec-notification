<?php declare(strict_types=1);

namespace NotificationBundle\Model\Entity\Message;

class MailMessage extends Message implements MailMessageInterface
{
    /** @var string $subject */
    protected $subject = '';

    /** @var string $from */
    protected $from = '';

    /** @var array $recipients */
    protected $recipients = [];

    /**
     * @return string
     */
    public function getSubject() : string
    {
        if (!$this->subject) {
            return $this->getTitle();
        }

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