<?php

namespace NotificationBundle\Model\Entity\Result;
use NotificationBundle\Model\Entity\MessageInterface;

/**
 * @package NotificationBundle\Model\Entity\Result
 */
class SenderResult
{
    /** @var bool $success */
    private $success = true;

    /** @var array $failureCodes */
    private $failureCodes = [];

    /** @var string[] $done */
    private $done = [];

    /** @var array $skipped */
    private $skipped = [];

    /**
     * @param bool  $success
     * @param array $failureMessages
     */
    public function __construct(bool $success = true, array $failureMessages = [])
    {
        $this->success         = $success;
        $this->failureCodes = $failureMessages;
    }

    /**
     * @return array
     */
    public function getFailureCodes(): array
    {
        return $this->failureCodes;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool|null $success
     * @return SenderResult
     */
    public function setSuccess($success)
    {
        $this->success = $success;
        return $this;
    }

    /**
     * @param array $failureCodes
     * @return SenderResult
     */
    public function setFailureCodes($failureCodes)
    {
        $this->failureCodes = $failureCodes;

        if (!empty($failureCodes)) {
            $this->success = false;
        }

        return $this;
    }

    /**
     * @param string $id
     * @param string $code
     * @return $this
     */
    public function addFailureCode($id, $code)
    {
        $this->failureCodes[$id] = $code;
        $this->success           = false;

        return $this;
    }

    /**
     * @param MessageInterface $message
     * @param bool             $skipped
     * @return $this
     */
    public function markAsDone(MessageInterface $message, $skipped = false)
    {
        $this->done[] = $message->getId();

        if ($skipped === true) {
            $this->skipped[] = $message->getId();
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getDoneCount()
    {
        return count($this->done);
    }

    /**
     * List of ids that were successfully sent
     *
     * @return \string[]
     */
    public function getProcessedElements()
    {
        return $this->done;
    }
}