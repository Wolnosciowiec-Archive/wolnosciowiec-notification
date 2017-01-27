<?php

namespace NotificationBundle\Controller\Validator;

use NotificationBundle\Factory\Message\MessageFactory;
use NotificationBundle\Model\Entity\Exception\IncompleteMessageParametersException;
use NotificationBundle\Model\Entity\Exception\InvalidMessageGroupException;
use NotificationBundle\Model\Entity\Exception\InvalidMessageTypeException;
use NotificationBundle\Model\Entity\MessageInterface;
use NotificationBundle\Model\Entity\Result\ValidationResult;
use NotificationBundle\Services\ConfigurationProvider\MessengerConfigurationProvider;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Validator for creation of a new MessageInterface object
 *
 * @package NotificationBundle\Controller\Validator
 */
class NewMessageValidator
{
    /** @var MessageFactory $factory */
    private $factory;

    /** @var MessengerConfigurationProvider $messengerConf */
    private $messengerConf;

    /** @var ValidatorInterface $validator */
    private $validator;

    /**
     * @param MessageFactory                 $factory
     * @param LoggerInterface                $logger
     * @param MessengerConfigurationProvider $messengerConfiguration
     * @param ValidatorInterface             $validator
     */
    public function __construct(
        MessageFactory $factory,
        LoggerInterface $logger,
        MessengerConfigurationProvider $messengerConfiguration,
        ValidatorInterface $validator
    ) {
        $this->factory       = $factory;
        $this->logger        = $logger;
        $this->messengerConf = $messengerConfiguration;
        $this->validator     = $validator;
    }

    /**
     * @param array  $data
     * @param string $messageType
     * @return ValidationResult
     */
    public function validate(array $data, string $messageType): ValidationResult
    {
        if (strlen($messageType) === 0) {
            return new ValidationResult(false, 'missing_message_type');
        }

        if (!is_array($data) || empty($data)) {
            return new ValidationResult(false, 'missing_input_array_data');
        }

        try {
            $message = $this->factory->createMessage($data, $messageType);
            $this->validateMessage($message);

        } catch (InvalidMessageTypeException $e) {
            return new ValidationResult(false, 'invalid_message_type');

        } catch (IncompleteMessageParametersException $e) {
            return new ValidationResult(false, 'missing_input_parameters', $e->getMessage());

        } catch (InvalidMessageGroupException $e) {
            return new ValidationResult(false, 'invalid_group_name', $e->getMessage());
        }

        /** @var array $errors */
        $errors = $this->validator->validate($message);

        if (count($errors) > 0) {

            $errorMessages = [];

            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return new ValidationResult(false, 'validation_failed', implode(', ', array_unique($errorMessages)));
        }

        // @codeCoverageIgnoreStart
        if (!isset($message)) {
            return new ValidationResult(false, 'cannot_create_message');
        }
        // @codeCoverageIgnoreEnd

        return new ValidationResult(true);
    }

    /**
     * @param MessageInterface $message
     *
     * @throws IncompleteMessageParametersException
     * @throws InvalidMessageTypeException
     * @throws InvalidMessageGroupException
     *
     * @return bool
     */
    public function validateMessage(MessageInterface $message): bool
    {
        if ($message->getGroupName() === null
            || $message->getContent() === null) {
            $this->logger->error('Invalid data object in request, missing required fields');

            throw new IncompleteMessageParametersException(
                'Missing "group_name" or "content" fields. ' .
                'Check documentation for input "message_type"');
        }

        // @codeCoverageIgnoreStart
        if (!$message instanceof MessageInterface) {
            $this->logger->critical(
                '"' . get_class($message) . '" should implement the MessageInterface' .
                ', this means probably a wrong class is accepted in your application config');

            throw new InvalidMessageTypeException('"' . get_class($message) . '" should implement the MessageInterface');
        }
        // @codeCoverageIgnoreEnd

        $foundGroup = false;

        foreach ($this->messengerConf->getMessengerGroupsByClass() as $groups) {

            if (in_array($message->getGroupName(), $groups)) {
                $foundGroup = true;
            }
        }

        if ($foundGroup === false) {
            throw new InvalidMessageGroupException('Message group not supported');
        }

        return true;
    }
}
