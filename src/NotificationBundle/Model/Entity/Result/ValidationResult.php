<?php

namespace NotificationBundle\Model\Entity\Result;

/**
 * @package NotificationBundle\Model\Entity\Result
 */
class ValidationResult
{
    /**
     * @var bool $success
     */
    private $success = false;

    /**
     * @var string $reason
     */
    private $reason = '';

    /**
     * @var string $message
     */
    private $message = null;

    /**
     * @param bool   $status
     * @param string $reason
     * @param string $message
     */
    public function __construct(bool $status, string $reason = '', string $message = null)
    {
        $this->success = $status;
        $this->reason  = $reason;
        $this->message = $message;
    }

    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
