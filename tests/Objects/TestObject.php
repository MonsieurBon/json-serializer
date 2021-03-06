<?php


namespace PJS\Tests\Objects;


class TestObject extends ParentTestObject
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

    /** @var \DateTimeImmutable */
    private $immutableDateProperty;

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
     * @var FactoryTestObject
     */
    private $factoryTestObject;

    /**
     * @return array
     */
    public function getArrayProperty()
    {
        return $this->arrayProperty;
    }

    /**
     * @param array $arrayProperty
     */
    public function setArrayProperty(array $arrayProperty): void
    {
        $this->arrayProperty = $arrayProperty;
    }

    /**
     * @return bool|null
     */
    public function getBooleanProperty()
    {
        return $this->booleanProperty;
    }

    /**
     * @param bool $booleanProperty
     */
    public function setBooleanProperty(bool $booleanProperty): void
    {
        $this->booleanProperty = $booleanProperty;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateProperty()
    {
        return $this->dateProperty;
    }

    /**
     * @param \DateTime $dateProperty
     */
    public function setDateProperty(\DateTime $dateProperty): void
    {
        $this->dateProperty = $dateProperty;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getImmutableDateProperty()
    {
        return $this->immutableDateProperty;
    }

    /**
     * @param \DateTimeImmutable $immutableDateProperty
     */
    public function setImmutableDateProperty(\DateTimeImmutable $immutableDateProperty): void
    {
        $this->immutableDateProperty = $immutableDateProperty;
    }

    /**
     * @return float
     */
    public function getFloatProperty()
    {
        return $this->floatProperty;
    }

    /**
     * @param float $floatProperty
     */
    public function setFloatProperty(float $floatProperty): void
    {
        $this->floatProperty = $floatProperty;
    }

    /**
     * @return int|null
     */
    public function getIntegerProperty()
    {
        return $this->integerProperty;
    }

    /**
     * @param int $integerProperty
     */
    public function setIntegerProperty(int $integerProperty): void
    {
        $this->integerProperty = $integerProperty;
    }

    /**
     * @return string|null
     */
    public function getStringProperty()
    {
        return $this->stringProperty;
    }

    /**
     * @param string $stringProperty
     */
    public function setStringProperty(string $stringProperty): void
    {
        $this->stringProperty = $stringProperty;
    }

    /**
     * @return NestedObject|null
     */
    public function getNestedObject()
    {
        return $this->nestedObject;
    }

    /**
     * @param NestedObject $nestedObject
     */
    public function setNestedObject(NestedObject $nestedObject): void
    {
        $this->nestedObject = $nestedObject;
    }

    public function getFactoryTestObject(): FactoryTestObject
    {
        return $this->factoryTestObject;
    }

    public function setFactoryTestObject(FactoryTestObject $factoryTestObject): void
    {
        $this->factoryTestObject = $factoryTestObject;
    }
}