<?php


namespace PJS\Tests\Objects;


class TestObject
{
    /**
     * @var array
     */
    private $arrayProperty;

    /**
     * @var bool
     */
    private $booleanProperty;

    /**
     * @var \DateTime
     */
    private $dateProperty;

    /**
     * @var float
     */
    private $floatProperty;

    /**
     * @var int
     */
    private $integerProperty;

    /**
     * @var string
     */
    private $stringProperty;

    /**
     * @var NestedObject
     */
    private $nestedObject;

    /**
     * @return array
     */
    public function getArrayProperty()
    {
        return $this->arrayProperty;
    }

    /**
     * @return bool|null
     */
    public function getBooleanProperty()
    {
        return $this->booleanProperty;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateProperty()
    {
        return $this->dateProperty;
    }

    /**
     * @return float
     */
    public function getFloatProperty()
    {
        return $this->floatProperty;
    }

    /**
     * @return int|null
     */
    public function getIntegerProperty()
    {
        return $this->integerProperty;
    }

    /**
     * @return string|null
     */
    public function getStringProperty()
    {
        return $this->stringProperty;
    }

    /**
     * @return NestedObject|null
     */
    public function getNestedObject()
    {
        return $this->nestedObject;
    }
}