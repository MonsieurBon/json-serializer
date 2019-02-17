<?php


namespace PJS;


use PJS\Exception\DeserializingException;
use PJS\Exception\SerializingException;
use PJS\Json\Deserializer;
use PJS\Json\Serializer;

class JsonSerializer
{
    private $configuration = array();

    /**
     * @param string $config_file_path
     */
    public function configure(string $config_file_path): void
    {
        $this->configuration = yaml_parse_file($config_file_path);
    }

    /**
     * @param string $json
     * @param Callable|string $class
     *
     * @return object|null
     * @throws DeserializingException
     */
    public function deserialize(string $json, $class)
    {
        $deserializer = new Deserializer($this->configuration);
        return $deserializer->deserialize($json, $class);
    }

    /**
     * @param $object
     * @return false|null|string
     * @throws SerializingException
     */
    public function serialize($object)
    {
        $serializer = new Serializer($this->configuration);
        return $serializer->serialize($object);
    }
}