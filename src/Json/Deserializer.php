<?php
/**
 * Created by PhpStorm.
 * User: fabian
 * Date: 19.11.18
 * Time: 17:59
 */

namespace PJS\Json;


use PJS\Exception\DeserializingException;

class Deserializer extends SerializerBase
{
    private const INVALID_VALUE_FOR_PROPERTY_OF_TYPE = 'Invalid value for property of type';

    /**
     * @param string $json
     * @param Callable|string $class
     *
     * @return object|null
     * @throws DeserializingException
     */
    public function deserialize(string $json, $class)
    {
        $array = json_decode($json, true);

        if (!is_array($array)) {
            return null;
        }

        return $this->deserializeArray($array, $class);
    }

    /**
     * @param array $array
     * @param Callable|string $class
     * @return null|object
     * @throws DeserializingException
     */
    private function deserializeArray(array $array, $class)
    {
        $className = $this->getClassName($array, $class);

        try {
            return $this->arrayToObject($array, $className);
        } catch (DeserializingException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new DeserializingException("Exception while deserializing", $e);
        }
    }

    /**
     * @param        $dataArray
     *
     * @param string $class
     *
     * @param string|null $factoryMethodName
     * @return object
     * @throws DeserializingException
     * @throws \ReflectionException
     */
    private function arrayToObject($dataArray, string $class, string $factoryMethodName = null)
    {
        if ($factoryMethodName !== null) {
            return $this->createFromFactoryMethod($dataArray, $class, $factoryMethodName);
        }

        $reflection = new \ReflectionClass($class);
        $object = $reflection->newInstanceWithoutConstructor();

        foreach ($dataArray as $propertyName => $value) {
            $propertyConfig = $this->getPropertyConfig($class, $propertyName);
            $propertyType = $propertyConfig[self::TYPE];

            switch ($propertyType) {
                case null:
                    $sanitizedValue = $value;
                    break;
                case 'array':
                    $sanitizedValue = $this->getArrayValue($value);
                    break;
                case 'boolean':
                    $sanitizedValue = $this->getBooleanValue($value);
                    break;
                case 'date':
                    $format = $propertyConfig[self::DATE_FORMAT];
                    $mutable = $propertyConfig[self::MUTABLE];
                    $sanitizedValue = $this->getDateValue($value, $format, $mutable);
                    break;
                case 'float':
                    $sanitizedValue = $this->getFloatValue($value);
                    break;
                case 'integer':
                    $sanitizedValue = $this->getIntegerValue($value);
                    break;
                case 'string':
                    $sanitizedValue = $this->getStringValue($value);
                    break;
                default:
                    $sanitizedValue = $this->arrayToObject($value, $propertyType, $propertyConfig[self::FACTORY_METHOD]);
            }

            $this->setProperty($reflection, $propertyConfig[self::PROPERTY_NAME] ?? $propertyName, $object, $sanitizedValue);
        }

        return $object;
    }

    /**
     * @param $value
     * @return mixed
     * @throws DeserializingException
     */
    private function getArrayValue($value)
    {
        if (!is_array($value)) {
            throw new DeserializingException(self::INVALID_VALUE_FOR_PROPERTY_OF_TYPE . ' array: ' . print_r($value, true));
        }
        return $value;
    }

    /**
     * @param $value
     * @return bool
     * @throws DeserializingException
     */
    private function getBooleanValue($value): bool
    {
        if (!is_bool($value)) {
            throw new DeserializingException(self::INVALID_VALUE_FOR_PROPERTY_OF_TYPE . ' boolean: ' . print_r($value, true));
        }
        return $value;
    }

    /**
     * @param $value
     * @param $format
     * @param bool $mutable
     * @return \DateTimeInterface
     * @throws DeserializingException
     */
    private function getDateValue($value, $format, $mutable = false): \DateTimeInterface
    {
        if ($mutable) {
            $date = \DateTime::createFromFormat($format, $value);
        } else {
            $date = \DateTimeImmutable::createFromFormat($format, $value);
        }

        if (!$date) {
            throw new DeserializingException(self::INVALID_VALUE_FOR_PROPERTY_OF_TYPE . ' date: ' . print_r($value, true));
        }

        return $date;
    }

    /**
     * @param $value
     * @return mixed
     * @throws DeserializingException
     */
    private function getFloatValue($value)
    {
        if (!is_numeric($value)) {
            throw new DeserializingException(self::INVALID_VALUE_FOR_PROPERTY_OF_TYPE . ' float: ' . print_r($value, true));
        }
        return $value;
    }

    /**
     * @param $value
     * @return int
     * @throws DeserializingException
     */
    private function getIntegerValue($value): int
    {
        if (!is_integer($value)) {
            throw new DeserializingException(self::INVALID_VALUE_FOR_PROPERTY_OF_TYPE . ' integer: ' . print_r($value, true));
        }
        return $value;
    }

    /**
     * @param $value
     * @return string
     * @throws DeserializingException
     */
    private function getStringValue($value): string
    {
        if (!is_string($value)) {
            throw new DeserializingException(self::INVALID_VALUE_FOR_PROPERTY_OF_TYPE . ' string: ' . print_r($value, true));
        }
        return $value;
    }

    /**
     * @param $reflection
     * @param $name
     * @param $object
     * @param $sanitizedValue
     * @throws \ReflectionException
     */
    private function setProperty(\ReflectionClass $reflection, $name, $object, $sanitizedValue): void
    {
        $property = $this->getProperty($reflection, $name);
        $property->setAccessible(true);
        $property->setValue($object, $sanitizedValue);
    }

    /**
     * @param array $array
     * @param $class
     * @return string
     * @throws DeserializingException
     */
    private function getClassName(array &$array, $class): string
    {
        if (is_callable($class)) {
            $className = $class($array);
        } else if (is_string($class)) {
            $className = $class;
        } else {
            throw new DeserializingException("Class is neither a callable nor a string.");
        }
        return $className;
    }

    /**
     * @param $data
     * @param string $class
     * @param string $factoryMethodName
     * @return mixed
     * @throws DeserializingException
     * @throws \ReflectionException
     */
    private function createFromFactoryMethod($data, string $class, string $factoryMethodName)
    {
        $reflection = new \ReflectionClass($class);
        if (!$reflection->hasMethod($factoryMethodName)) {
            throw new DeserializingException(sprintf("No such factory method '%s'", $factoryMethodName));
        }

        $factoryMethod = $reflection->getMethod($factoryMethodName);

        if ($factoryMethod->isStatic()) {
            return $factoryMethod->invoke(null, $data);
        } else {
            throw new DeserializingException(sprintf("Factory method '%s' must be static", $factoryMethodName));
        }
    }

    /**
     * @param \ReflectionClass $reflection
     * @param $name
     * @return \ReflectionProperty
     * @throws \ReflectionException
     */
    private function getProperty(\ReflectionClass $reflection, $name): \ReflectionProperty
    {
        $parent = $reflection->getParentClass();
        if ($reflection->hasProperty($name) || $parent === false) {
            return $reflection->getProperty($name);
        } else {
            return $this->getProperty($parent, $name);
        }
    }
}