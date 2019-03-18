<?php
/**
 * Created by PhpStorm.
 * User: fabian
 * Date: 22.11.18
 * Time: 08:17
 */

namespace PJS\Json;


class SerializerBase
{
    protected const TYPE = 'type';
    protected const DATE_FORMAT = 'dateFormat';
    protected const FACTORY_METHOD = 'factoryMethod';
    protected const MUTABLE = 'mutable';

    private const DEFAULT_CONFIG = array(
        self::TYPE => null,
        self::DATE_FORMAT => 'd-m-Y H:i:s\Z',
        self::FACTORY_METHOD => null,
        self::MUTABLE => false
    );

    private $configuration;

    public function __construct($configuration = array())
    {
        $this->configuration = $configuration;
    }

    /**
     * @param string $class
     * @return array
     */
    private function getObjectConfig(string $class): array
    {
        return $this->configuration[$class] ?? array();
    }

    /**
     * @param string $class
     * @param $propertyName
     * @return array
     */
    protected function getPropertyConfig(string $class, $propertyName): array
    {
        $objectConfig = $this->getObjectConfig($class);
        $propertyConfig = $objectConfig[$propertyName] ?? null;

        if (is_array($propertyConfig)) {
            $mergedPropertyConfig = array_merge(self::DEFAULT_CONFIG, $propertyConfig);
        } else {
            $mergedPropertyConfig = array_merge(self::DEFAULT_CONFIG, [self::TYPE => $propertyConfig]);
        }

        return $mergedPropertyConfig;
    }
}