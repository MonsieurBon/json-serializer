<?php

declare(strict_types=1);

namespace PJS\Tests\Objects;

class ParentTestObject
{
    /** @var string */
    private $parentStringProperty;

    /**
     * @return string
     */
    public function getParentStringProperty(): string
    {
        return $this->parentStringProperty;
    }

    /**
     * @param string $parentStringProperty
     */
    public function setParentStringProperty(string $parentStringProperty): void
    {
        $this->parentStringProperty = $parentStringProperty;
    }
}