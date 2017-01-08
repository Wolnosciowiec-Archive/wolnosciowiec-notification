<?php

namespace NotificationBundle\Controller\Validator;

use NotificationBundle\Factory\Message\MessageFactory;
use NotificationBundle\Model\Entity\Exception\IncompleteMessageParametersException;
use NotificationBundle\Model\Entity\Exception\InvalidMessageTypeException;
use NotificationBundle\Model\Entity\Result\ValidationResult;

/**
 * Validator for creation of a new MessageInterface object
 *
 * @package NotificationBundle\Controller\Validator
 */
class NewMessageValidator
{
    /** @var MessageFactory $factory */
    private $factory;

    /**
     * @param MessageFactory $factory
     */
    public function __construct(MessageFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param array  $data
     * @param string $messageType
     * @return ValidationResult
     */
    public function validate($data, $messageType)
    {
        if (strlen($messageType) === 0) {
            return new ValidationResult(false, 'missing_message_type');
        }

        if (!is_array($data) || empty($data)) {
            return new ValidationResult(false, 'missing_input_array_data');
        }

        try {
            $message = $this->factory->createMessage($data, $messageType);
        }
        catch (InvalidMessageTypeException $e) {
            return new ValidationResult(false, 'invalid_message_type');
        }
        catch (IncompleteMessageParametersException $e) {
            return new ValidationResult(false, 'missing_input_parameters', $e->getMessage());
        }

        if (!isset($message)) {
            return new ValidationResult(false, 'cannot_create_message');
        }

        return new ValidationResult(true);
    }
}