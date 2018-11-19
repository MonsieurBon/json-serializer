<?php
/**
 * Created by PhpStorm.
 * User: fabian
 * Date: 19.11.18
 * Time: 17:59
 */

namespace PJS\Json;


use PJS\Exception\DeserializingException;
use PJS\JsonSerializer;

class Deserializer
{
    private const INVALID_VALUE_FOR_PROPERTY_OF_TYPE = 'Invalid value for property of type';

    private $configuration;

    public function __construct($configuration = array())
    {
        $this->configuration = $configuration;
    }

    /**
     * @param string $json
     * @param string $class
     *
     * @return object|null
     * @throws DeserializingException
     */
    public function deserialize(string $json, string $class)
    {
        $array = json_decode($json, true);

        if (!is_array($array)) {
            return null;
        }

        return $this->deserializeArray($array, $class);
    }

    /**
     * @param array $array
     * @param string $class
     * @return null|object
     * @throws DeserializingException
     */
    private function deserializeArray(array $array, string $class)
    {
        try {
            return $this->arrayToObject($array, $class);
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
     * @return object
     * @throws \ReflectionException
     * @throws DeserializingException
     */
    private function arrayToObject($dataArray, string $class)
    {
        $objectConfig = $this->configuration[$class] ?? array();

        $reflection = new \ReflectionClass($class);
        $object = $reflection->newInstanceWithoutConstructor();

        foreach ($dataArray as $propertyName => $value) {
            $propertyConfig = $this->getPropertyConfig($objectConfig, $propertyName);
            $propertyType = $propertyConfig['type'];

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
                    $format = $propertyConfig['dateFormat'];
                    $sanitizedValue = $this->getDateValue($value, $format);
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
                    $sanitizedValue = $this->arrayToObject($value, $propertyType);
            }

            $this->setProperty($reflection, $propertyName, $object, $sanitizedValue);
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
     * @return \DateTime
     * @throws DeserializingException
     */
    private function getDateValue($value, $format)
    {
        try {
            return \DateTime::createFromFormat($format, $value);
        } catch(\Exception $e) {
            throw new DeserializingException(self::INVALID_VALUE_FOR_PROPERTY_OF_TYPE . ' date: ' . print_r($value, true), $e);
        }
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
        $stringValue = $value;
        return $stringValue;
    }

    /**
     * @param $reflection
     * @param $name
     * @param $object
     * @param $sanitizedValue
     */
    private function setProperty(\ReflectionClass $reflection, $name, $object, $sanitizedValue): void
    {
        $property = $reflection->getProperty($name);
        $property->setAccessible(true);
        $property->setValue($object, $sanitizedValue);
    }

    /**
     * @param $objectConfig
     * @param $propertyName
     * @return array
     */
    private function getPropertyConfig($objectConfig, $propertyName): array
    {
        $mergedPropertyConfig = JsonSerializer::DEFAULT_CONFIG;

        $propertyConfig = $objectConfig[$propertyName] ?? null;
        if (is_array($propertyConfig)) {
            foreach($propertyConfig as $configName => $configValue) {
                $mergedPropertyConfig[$configName] = $configValue;
            }
        } else {
            $mergedPropertyConfig['type'] = $propertyConfig;
        }

        return $mergedPropertyConfig;
    }
}