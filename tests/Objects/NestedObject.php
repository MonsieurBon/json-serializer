<?php


namespace PJS\Tests\Objects;


class NestedObject
{
    /**
     * @var string
     */
    private $stringProperty;

    /**
     * @var integer
     */
    private $integerProperty;

    /**
     * @var boolean
     */
    private $booleanProperty;

    /**
     * @var \DateTime
     */
    private $dateProperty;

    /**
     * @return string|null
     */
    public function getStringProperty()
    {
        return $this->stringProperty;
    }

    /**
     * @return int|null
     */
    public function getIntegerProperty()
    {
        return $this->integerProperty;
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


}