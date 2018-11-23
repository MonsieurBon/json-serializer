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

    private const DEFAULT_CONFIG = array(
        self::TYPE => null,
        self::DATE_FORMAT => 'd-m-Y H:i:s\Z'
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
        $objectConfig = $this->configuration[$class] ?? array();
        return $objectConfig;
    }

    /**
     * @param string $class
     * @param $propertyName
     * @return array
     */
    protected function getPropertyConfig(string $class, $propertyName): array
    {
        $objectConfig = $this->getObjectConfig($class);

        $mergedPropertyConfig = self::DEFAULT_CONFIG;

        $propertyConfig = $objectConfig[$propertyName] ?? null;
        if (is_array($propertyConfig)) {
            foreach ($propertyConfig as $configName => $configValue) {
                $mergedPropertyConfig[$configName] = $configValue;
            }
        } else {
            $mergedPropertyConfig[self::TYPE] = $propertyConfig;
        }

        return $mergedPropertyConfig;
    }
}