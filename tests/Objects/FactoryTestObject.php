<?php

declare(strict_types=1);

namespace PJS\Tests\Objects;

class FactoryTestObject implements \JsonSerializable
{
    /** @var string */
    private $foo;

    private function __construct(string $foo)
    {
        $this->foo = $foo;
    }

    public static function fromString(string $foo): self
    {
        return new static($foo);
    }

    public function nonStaticFactory(string $foo): self
    {
        return new self($foo);
    }

    public function toString(): string
    {
        return $this->foo;
    }

    public function jsonSerialize()
    {
        return $this->foo;
    }
}