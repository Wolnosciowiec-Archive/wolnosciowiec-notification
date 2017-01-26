<?php declare(strict_types=1);

namespace NotificationBundle\Model\Entity\Base;

/**
 * Automatically serialize all properties in the class
 * and its parents
 *
 * @package NotificationBundle\Model\Entity\Base
 */
class AutoSerializable implements \Serializable
{
    /**
     * Returns list of all declared non-static properties
     *
     * @return array
     */
    private function getOwnPropertiesList()
    {
        $properties = [];
        $ref = new \ReflectionObject($this);

        foreach ($ref->getProperties() as $property) {

            if ($property->isStatic()) {
                continue;
            }

            $properties[] = $property->getName();
        }

        return $properties;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        $fields = [];

        foreach ($this->getOwnPropertiesList() as $propertyName) {
            $property = new \ReflectionProperty(get_called_class(), $propertyName);
            $property->setAccessible(true);

            $fields[$propertyName] = $property->getValue($this);
        }

        return serialize($fields);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        foreach ($data as $propertyName => $value) {
            $property = new \ReflectionProperty(get_called_class(), $propertyName);
            $property->setAccessible(true);
            $property->setValue($this, $value);
        }
    }
}