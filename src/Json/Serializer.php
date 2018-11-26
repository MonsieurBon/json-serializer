<?php
/**
 * Created by PhpStorm.
 * User: fabian
 * Date: 19.11.18
 * Time: 17:53
 */

namespace PJS\Json;


use PJS\Exception\SerializingException;

class Serializer extends SerializerBase
{
    public function __construct($configuration = array())
    {
        parent::__construct($configuration);
    }

    /**
     * @param $object
     * @return false|null|string
     */
    public function serialize($object)
    {
        if ($object == null) {
            return null;
        }

        $array = $this->serializeObject($object);
        return $this->encodePropertyArray($array);
    }

    /**
     * @param $array
     * @return string
     */
    private function encodePropertyArray($array): string
    {
        if (empty($array)) {
            return json_encode(new \stdClass());
        }

        return json_encode($array);
    }

    /**
     * @param $object
     * @param \ReflectionProperty $property
     * @return mixed
     */
    private function encodeValue($object, \ReflectionProperty $property)
    {
        $propertyConfig = $this->getPropertyConfig(get_class($object), $property->getName());
        $value = $property->getValue($object);

        switch ($propertyConfig[self::TYPE]) {
            case null:
            case 'array':
            case 'boolean':
            case 'float':
            case 'integer':
            case 'string':
                return $value;
            case 'date':
                return $this->encodeDate($value, $propertyConfig);
            default:
                return $this->serializeObject($value);
        }
    }

    /**
     * @param $date
     * @param $propertyConfig
     * @return string
     */
    private function encodeDate($date, $propertyConfig): string
    {
        if ($date instanceof \DateTimeInterface) {
            $format = $propertyConfig[self::DATE_FORMAT];
            return $date->format($format);
        } else {
            return $date;
        }
    }

    /**
     * @param $object
     * @return mixed
     */
    private function serializeObject($object)
    {
        try {
            $reflection = new \ReflectionClass($object);
            $properties = $reflection->getProperties();

            $array = array();

            foreach ($properties as $property) {
                $property->setAccessible(true);
                if ($property->getValue($object) !== null) {
                    $array[$property->getName()] = $this->encodeValue($object, $property);
                }
            }
            return $array;
        } catch (\ReflectionException $e) {
            return $object;
        }
    }
}