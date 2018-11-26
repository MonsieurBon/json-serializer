<?php

namespace PJS\Tests;


use PHPUnit\Framework\TestCase;
use PJS\Exception\SerializingException;
use PJS\JsonSerializer;
use PJS\Tests\Objects\NestedObject;
use PJS\Tests\Objects\TestObject;

class SerializeTest extends TestCase
{
    public function testSerializeNull()
    {
        $serializer = new JsonSerializer();
        $json = $serializer->serialize(null);

        $this->assertNull($json);
    }

    public function testDeserializeEmptyJson()
    {
        $serializer = new JsonSerializer();
        $json = $serializer->serialize(new TestObject());

        $this->assertEquals("{}", $json);
    }

    public function testSerializeStringProperty()
    {
        $object = new TestObject();
        $object->setStringProperty('foo');

        $serializer = new JsonSerializer();
        $json = $serializer->serialize($object);

        $this->assertEquals('{"stringProperty":"foo"}', $json);
    }

    public function testSerializeIntegerProperty()
    {
        $object = new TestObject();
        $object->setIntegerProperty(42);

        $serializer = new JsonSerializer();
        $json = $serializer->serialize($object);

        $this->assertEquals('{"integerProperty":42}', $json);
    }

    public function testSerializeBooleanProperty()
    {
        $object = new TestObject();
        $object->setBooleanProperty(true);

        $serializer = new JsonSerializer();
        $json = $serializer->serialize($object);

        $this->assertEquals('{"booleanProperty":true}', $json);
    }

    public function testSerializeUnconfiguredDateProperty()
    {
        $object = new TestObject();
        $object->setDateProperty(new \DateTime('01-11-2018 10:49:53Z'));

        $serializer = new JsonSerializer();
        $json = $serializer->serialize($object);

        $this->assertEquals('{"dateProperty":{"date":"2018-11-01 10:49:53.000000","timezone_type":2,"timezone":"Z"}}', $json);
    }

    public function testSerializeConfiguredDateProperty()
    {
        $object = new TestObject();
        $object->setDateProperty(new \DateTime('01-11-2018 10:49:53Z'));

        $serializer = new JsonSerializer();
        $serializer->configure(__DIR__ . '/resources/config.yml');

        $json = $serializer->serialize($object);

        $this->assertEquals('{"dateProperty":"2018-11-01T10:49:53Z"}', $json);
    }

    public function testSerializeInvalidDate()
    {
        $object = new TestObject();
        $object->setStringProperty('foo');

        $serializer = new JsonSerializer();
        $serializer->configure(__DIR__ . '/resources/config_invalid_date.yml');

        $json = $serializer->serialize($object);

        $this->assertEquals('{"stringProperty":"foo"}', $json);
    }

    public function testSerializeArrayProperty()
    {
        $object = new TestObject();
        $object->setArrayProperty(array('a', 'b', 'c', 1, 2, 3));

        $serializer = new JsonSerializer();
        $json = $serializer->serialize($object);

        $this->assertEquals('{"arrayProperty":["a","b","c",1,2,3]}', $json);
    }

    public function testSerializeUnconfiguredFloatProperty()
    {
        $object = new TestObject();
        $object->setFloatProperty(1.2e-5);

        $serializer = new JsonSerializer();
        $json = $serializer->serialize($object);

        $this->assertEquals('{"floatProperty":1.2e-5}', $json);
    }

    public function testSerializeConfiguredFloatProperty()
    {
        $object = new TestObject();
        $object->setFloatProperty(1.2e-5);

        $serializer = new JsonSerializer();
        $serializer->configure(__DIR__ . '/resources/config.yml');
        $json = $serializer->serialize($object);

        $this->assertEquals('{"floatProperty":1.2e-5}', $json);
    }

    public function testSerializeMultipleProperties()
    {
        $object = new TestObject();
        $object->setBooleanProperty(true);
        $object->setIntegerProperty(42);
        $object->setStringProperty('foo');

        $serializer = new JsonSerializer();
        $json = $serializer->serialize($object);

        $this->assertEquals('{"booleanProperty":true,"integerProperty":42,"stringProperty":"foo"}', $json);
    }

    public function testSerializeNestedObjectWithoutConfiguration()
    {
        $nestedObject = new NestedObject();
        $nestedObject->setBooleanProperty(false);
        $nestedObject->setIntegerProperty(24);
        $nestedObject->setStringProperty('bar');

        $object = new TestObject();
        $object->setBooleanProperty(true);
        $object->setIntegerProperty(42);
        $object->setStringProperty('foo');
        $object->setNestedObject($nestedObject);

        $serializer = new JsonSerializer();
        $json = $serializer->serialize($object);

        $this->assertEquals('{"booleanProperty":true,"integerProperty":42,"stringProperty":"foo","nestedObject":{}}', $json);
    }

    public function testSerializeNestedObjectWithConfiguration()
    {
        $nestedObject = new NestedObject();
        $nestedObject->setBooleanProperty(false);
        $nestedObject->setIntegerProperty(24);
        $nestedObject->setStringProperty('bar');

        $object = new TestObject();
        $object->setBooleanProperty(true);
        $object->setIntegerProperty(42);
        $object->setStringProperty('foo');
        $object->setNestedObject($nestedObject);

        $serializer = new JsonSerializer();
        $serializer->configure(__DIR__ . '/resources/config.yml');
        $json = $serializer->serialize($object);

        $this->assertEquals('{"booleanProperty":true,"integerProperty":42,"stringProperty":"foo","nestedObject":{"booleanProperty":false,"integerProperty":24,"stringProperty":"bar"}}', $json);
    }

    public function testSerializeInvalidObjectThrowsException()
    {
        $object = new TestObject();
        $object->setStringProperty('foo');

        $serializer = new JsonSerializer();
        $serializer->configure(__DIR__ . '/resources/config_invalid_nested_object.yml');
        $json = $serializer->serialize($object);

        $this->assertEquals('{"stringProperty":"foo"}', $json);
    }
}
