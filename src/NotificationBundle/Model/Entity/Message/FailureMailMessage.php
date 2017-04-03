<?php declare(strict_types = 1);

namespace NotificationBundle\Model\Entity\Message;
use NotificationBundle\Model\Entity\WithRendererInterface;

/**
 * @package NotificationBundle\Model\Entity\Message\Message
 */
class FailureMailMessage extends MailMessage implements WithRendererInterface
{
    /** @var string $serviceName */
    private $serviceName;

    /** @var string $requestString */
    private $requestString;

    /** @var string[] $trace */
    private $trace = [];

    /** @var string $filePath */
    private $filePath = '';

    /** @var int $fileLine */
    private $fileLine = 0;

    /** @var string $exceptionMessage */
    private $exceptionMessage = '';

    /**
     * @inheritdoc
     */
    public function getSubject() : string
    {
        return '[' . $this->getServiceName() . '] Failure: ' . $this->exceptionMessage;
    }

    /**
     * @see WithRendererInterface
     */
    public function getContent(): string
    {
        // content is rendered using WithRendererInterface
        return '-';
    }

    /**
     * @see WithRendererInterface
     * @return string
     */
    public function getTemplateName() : string
    {
        return '@app/Messages/FailureMessage.html.twig';
    }

    /**
     * @param string[] $trace
     * @return $this
     */
    public function setTrace(array $trace)
    {
        return $this->trace = $trace;
    }

    /**
     * @param string $filePath
     * @return FailureMailMessage
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
        return $this;
    }

    /**
     * @param int $fileLine
     * @return FailureMailMessage
     */
    public function setFileLine($fileLine)
    {
        $this->fileLine = $fileLine;
        return $this;
    }

    /**
     * @param string $exceptionMessage
     * @return FailureMailMessage
     */
    public function setExceptionMessage($exceptionMessage)
    {
        $this->exceptionMessage = $exceptionMessage;
        return $this;
    }

    /**
     * @return \string[]
     */
    public function getTrace() : array
    {
        return $this->trace;
    }

    /**
     * @return string
     */
    public function getFilePath() : string
    {
        return $this->filePath;
    }

    /**
     * @return int
     */
    public function getFileLine() : int
    {
        return $this->fileLine;
    }

    /**
     * @return string
     */
    public function getExceptionMessage() : string
    {
        return $this->exceptionMessage;
    }

    /**
     * @return string
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * @return string
     */
    public function getRequestString()
    {
        return $this->requestString;
    }

    /**
     * @param string $serviceName
     * @return FailureMailMessage
     */
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
        return $this;
    }

    /**
     * @param string $requestString
     * @return FailureMailMessage
     */
    public function setRequestString($requestString)
    {
        $this->requestString = $requestString;
        return $this;
    }
}
