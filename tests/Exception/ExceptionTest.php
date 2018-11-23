<?php
/**
 * Created by PhpStorm.
 * User: fabian
 * Date: 23.11.18
 * Time: 17:18
 */

namespace PJS\Tests\Exception;


use PHPUnit\Framework\TestCase;
use PJS\Exception\DeserializingException;
use PJS\Exception\SerializingException;

class ExceptionTest extends TestCase
{
    public function testCanInstantiateDeserializingException()
    {
        $previous = new \Exception('foo');
        $exception = new DeserializingException('bar', $previous);

        $this->assertTrue($exception instanceof DeserializingException);
        $this->assertEquals('bar', $exception->getMessage());
        $this->assertEquals($previous, $exception->getPrevious());
    }

    public function testCanInstantiateSerializingException()
    {
        $previous = new \Exception('foo');
        $exception = new SerializingException('bar', $previous);

        $this->assertTrue($exception instanceof SerializingException);
        $this->assertEquals('bar', $exception->getMessage());
        $this->assertEquals($previous, $exception->getPrevious());
    }
}