<?php


namespace PJS;


use PJS\Exception\DeserializingException;
use PJS\Json\Deserializer;

class JsonSerializer
{

    const DEFAULT_CONFIG = array(
        'type' => null,
        'dateFormat' => 'd-m-Y H:i:s\Z'
    );

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
     * @param string $class
     *
     * @return object|null
     * @throws DeserializingException
     */
    public function deserialize(string $json, string $class)
    {
        $deserializer = new Deserializer($this->configuration);
        return $deserializer->deserialize($json, $class);
    }
}