<?php


namespace PJS\Tests\Objects;


class NestedObject
{
    /**
     * @var boolean
     */
    private $booleanProperty;

    /**
     * @var \DateTime
     */
    private $dateProperty;

    /**
     * @var integer
     */
    private $integerProperty;

    /**
     * @var string
     */
    private $stringProperty;

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
}