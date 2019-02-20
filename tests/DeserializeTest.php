<?php

namespace PJS\Tests;


use PHPUnit\Framework\TestCase;
use PJS\Exception\DeserializingException;
use PJS\Exception\SerializingException;
use PJS\JsonSerializer;
use PJS\Tests\Objects\FactoryTestObject;
use PJS\Tests\Objects\NestedObject;
use PJS\Tests\Objects\TestObject;

class DeserializeTest extends TestCase
{
    public function testDeserializeNullJson()
    {
        $null_json = json_encode(null);
        $serializer = new JsonSerializer();
        $object = $serializer->deserialize($null_json, TestObject::class);

        $this->assertNull($object);
    }

    public function testDeserializeEmptyJson()
    {
        $empty_json = json_encode(new \stdClass());
        $serializer = new JsonSerializer();
        $object = $serializer->deserialize($empty_json, TestObject::class);

        $this->assertNotNull($object);
        $this->assertNull($object->getStringProperty());
        $this->assertNull($object->getIntegerProperty());
        $this->assertNull($object->getBooleanProperty());
    }

    public function testDeserializeStringProperty()
    {
        $json = json_encode(array('stringProperty' => 'foo'));
        $serializer = new JsonSerializer();
        $object = $serializer->deserialize($json, TestObject::class);

        $this->assertNotNull($object);
        $this->assertEquals('foo', $object->getStringProperty());
        $this->assertNull($object->getIntegerProperty());
        $this->assertNull($object->getBooleanProperty());
    }

    public function testDeserializeIntegerProperty()
    {
        $json = json_encode(array('integerProperty' => 42));
        $serializer = new JsonSerializer();
        $object = $serializer->deserialize($json, TestObject::class);

        $this->assertNotNull($object);
        $this->assertNull($object->getStringProperty());
        $this->assertEquals(42, $object->getIntegerProperty());
        $this->assertNull($object->getBooleanProperty());
    }

    public function testDeserializeBooleanProperty()
    {
        $json = json_encode(array('booleanProperty' => true));
        $serializer = new JsonSerializer();
        $object = $serializer->deserialize($json, TestObject::class);

        $this->assertNotNull($object);
        $this->assertNull($object->getStringProperty());
        $this->assertNull($object->getIntegerProperty());
        $this->assertTrue($object->getBooleanProperty());
    }

    public function testDeserializeUnconfiguredDateProperty()
    {
        $json = json_encode(array('dateProperty' => '2018-11-01\T10:49:53Z'));
        $serializer = new JsonSerializer();

        /** @var TestObject $object */
        $object = $serializer->deserialize($json, TestObject::class);

        $this->assertNotNull($object);
        $this->assertNull($object->getStringProperty());
        $this->assertNull($object->getIntegerProperty());
        $this->assertNull($object->getBooleanProperty());
        $this->assertEquals('2018-11-01\T10:49:53Z', $object->getDateProperty());
    }

    public function testDeserializeConfiguredDateProperty()
    {
        $json = json_encode(array('dateProperty' => '2018-11-01T10:49:53Z'));
        $serializer = new JsonSerializer();
        $serializer->configure(__DIR__ . '/resources/config.yml');

        /** @var TestObject $object */
        $object = $serializer->deserialize($json, TestObject::class);

        $this->assertNotNull($object);
        $this->assertNull($object->getStringProperty());
        $this->assertNull($object->getIntegerProperty());
        $this->assertNull($object->getBooleanProperty());
        $this->assertEquals(1541069393, $object->getDateProperty()->getTimestamp());
    }

    public function testDeserializeUnconfiguredArrayProperty()
    {
        $json = json_encode(array('arrayProperty' => array('a', 'b', 'c', 1, 2, 3)));
        $serializer = new JsonSerializer();

        /** @var TestObject $object */
        $object = $serializer->deserialize($json, TestObject::class);

        $this->assertNotNull($object);
        $this->assertNull($object->getBooleanProperty());
        $this->assertNull($object->getDateProperty());
        $this->assertNull($object->getIntegerProperty());
        $this->assertNull($object->getStringProperty());
        $this->assertEquals(array('a', 'b', 'c', 1, 2, 3), $object->getArrayProperty());
    }

    public function testDeserializeConfiguredArrayProperty()
    {
        $json = json_encode(array('arrayProperty' => array('a', 'b', 'c', 1, 2, 3)));
        $serializer = new JsonSerializer();
        $serializer->configure(__DIR__ . '/resources/config.yml');

        /** @var TestObject $object */
        $object = $serializer->deserialize($json, TestObject::class);

        $this->assertNotNull($object);
        $this->assertNull($object->getBooleanProperty());
        $this->assertNull($object->getDateProperty());
        $this->assertNull($object->getIntegerProperty());
        $this->assertNull($object->getStringProperty());
        $this->assertEquals(array('a', 'b', 'c', 1, 2, 3), $object->getArrayProperty());
    }

    public function testDeserializeUnconfiguredFloatProperty()
    {
        $json = json_encode(array('floatProperty' => 1.2e-5));
        $serializer = new JsonSerializer();

        /** @var TestObject $object */
        $object = $serializer->deserialize($json, TestObject::class);

        $this->assertNotNull($object);
        $this->assertEquals(1.2e-5, $object->getFloatProperty());
    }

    public function testDeserializeConfiguredFloatProperty()
    {
        $json = json_encode(array('floatProperty' => 1.2e-5));
        $serializer = new JsonSerializer();
        $serializer->configure(__DIR__ . '/resources/config.yml');

        /** @var TestObject $object */
        $object = $serializer->deserialize($json, TestObject::class);

        $this->assertNotNull($object);
        $this->assertEquals(1.2e-5, $object->getFloatProperty());
    }

    public function testDeserializeMultipleProperties()
    {
        $json = json_encode(array(
            'stringProperty' => 'foo',
            'integerProperty' => 42,
            'booleanProperty' => true
        ));
        $serializer = new JsonSerializer();
        $object = $serializer->deserialize($json, TestObject::class);

        $this->assertNotNull($object);
        $this->assertEquals('foo', $object->getStringProperty());
        $this->assertEquals(42, $object->getIntegerProperty());
        $this->assertTrue($object->getBooleanProperty());https://duckduckgo.com/?q=php+date+parse&t=canonical
    }

    public function testDeserializeNestedObjectWithoutConfiguration()
    {
        $json = json_encode(array(
            'stringProperty' => 'foo',
            'integerProperty' => 42,
            'booleanProperty' => true,
            'nestedObject' => array(
                'stringProperty' => 'foo',
                'integerProperty' => 42,
                'booleanProperty' => false
            )
        ));

        $serializer = new JsonSerializer();

        /** @var TestObject $object */
        $object = $serializer->deserialize($json, TestObject::class);

        $this->assertNotNull($object);
        $this->assertTrue($object instanceof TestObject);
        $this->assertEquals('foo', $object->getStringProperty());
        $this->assertEquals(42, $object->getIntegerProperty());
        $this->assertTrue($object->getBooleanProperty());
        $this->assertEquals(array(
            'stringProperty' => 'foo',
            'integerProperty' => 42,
            'booleanProperty' => false
        ), $object->getNestedObject());
    }

    public function testDeserializeNestedObjectWithConfiguration()
    {
        $json = json_encode(array(
            'stringProperty' => 'foo',
            'integerProperty' => 42,
            'booleanProperty' => true,
            'nestedObject' => array(
                'stringProperty' => 'bar',
                'integerProperty' => 21,
                'booleanProperty' => false
            )
        ));

        $serializer = new JsonSerializer();
        $serializer->configure(__DIR__ . '/resources/config.yml');

        /** @var TestObject $object */
        $object = $serializer->deserialize($json, TestObject::class);

        $this->assertNotNull($object);
        $this->assertTrue($object instanceof TestObject);
        $this->assertEquals('foo', $object->getStringProperty());
        $this->assertEquals(42, $object->getIntegerProperty());
        $this->assertTrue($object->getBooleanProperty());

        $nestedObject = $object->getNestedObject();
        $this->assertNotNull($nestedObject);
        $this->assertTrue($nestedObject instanceof NestedObject);
        $this->assertEquals('bar', $nestedObject->getStringProperty());
        $this->assertEquals(21, $nestedObject->getIntegerProperty());
        $this->assertFalse($nestedObject->getBooleanProperty());
    }

    public function testGetClassnameFromCallable()
    {
        $json = json_encode(array('stringProperty' => 'foo'));
        $serializer = new JsonSerializer();

        $called = false;
        $object = $serializer->deserialize($json, function($array) use (&$called) {
            $called = true;
            return TestObject::class;
        });

        $this->assertNotNull($object);
        $this->assertTrue($called);
        $this->assertTrue($object instanceof TestObject);
        $this->assertEquals('foo', $object->getStringProperty());
        $this->assertNull($object->getIntegerProperty());
        $this->assertNull($object->getBooleanProperty());
    }

    public function testOnlyStringOrCallableAccepted()
    {
        $this->expectException(DeserializingException::class);
        $this->expectExceptionMessage('Class is neither a callable nor a string.');
        $json = json_encode(array('stringProperty' => 'foo'));
        $serializer = new JsonSerializer();
        $serializer->deserialize($json, 5);
    }

    public function testDeserializeInvalidString()
    {
        $this->expectException(DeserializingException::class);
        $this->expectExceptionMessage('Invalid value for property of type string: 42');

        $json = json_encode(array(
            'stringProperty' => 42
        ));

        $serializer = new JsonSerializer();
        $serializer->configure(__DIR__ . '/resources/config.yml');

        $serializer->deserialize($json, TestObject::class);
    }

    public function testDeserializeInvalidInteger()
    {
        $this->expectException(DeserializingException::class);
        $this->expectExceptionMessage('Invalid value for property of type integer: foo');

        $json = json_encode(array(
            'integerProperty' => 'foo'
        ));

        $serializer = new JsonSerializer();
        $serializer->configure(__DIR__ . '/resources/config.yml');

        $serializer->deserialize($json, TestObject::class);
    }

    public function testDeserializeInvalidBoolean()
    {
        $this->expectException(DeserializingException::class);
        $this->expectExceptionMessage('Invalid value for property of type boolean: foo');

        $json = json_encode(array(
            'booleanProperty' => 'foo'
        ));

        $serializer = new JsonSerializer();
        $serializer->configure(__DIR__ . '/resources/config.yml');

        $serializer->deserialize($json, TestObject::class);
    }

    public function testDeserializerInvalidArray()
    {
        $this->expectException(DeserializingException::class);
        $this->expectExceptionMessage('Invalid value for property of type array: foo');

        $json = json_encode(array(
            'arrayProperty' => 'foo'
        ));

        $serializer = new JsonSerializer();
        $serializer->configure(__DIR__ . '/resources/config.yml');

        $serializer->deserialize($json, TestObject::class);
    }

    public function testDeserializeInvalidDate()
    {
        $this->expectException(DeserializingException::class);
        $this->expectExceptionMessage('Invalid value for property of type date: foo');

        $json = json_encode(array(
            'dateProperty' => 'foo'
        ));

        $serializer = new JsonSerializer();
        $serializer->configure(__DIR__ . '/resources/config.yml');

        $serializer->deserialize($json, TestObject::class);
    }

    public function testDeserializeInvalidFloat()
    {
        $this->expectException(DeserializingException::class);
        $this->expectExceptionMessage('Invalid value for property of type float: foo');

        $json = json_encode(array(
            'floatProperty' => 'foo'
        ));

        $serializer = new JsonSerializer();
        $serializer->configure(__DIR__ . '/resources/config.yml');

        $serializer->deserialize($json, TestObject::class);
    }

    public function testInvalidProperty()
    {
        $this->expectException(DeserializingException::class);
        $this->expectExceptionMessage('Exception while deserializing');

        $json = json_encode(array(
            'invalidProperty' => 'invalid data'
        ));

        $serializer = new JsonSerializer();
        $serializer->deserialize($json, TestObject::class);
    }

    public function testCreateFromFactoryMethod()
    {
        $json = json_encode(array(
            'factoryTestObject' => 'foobar'
        ));

        $serializer = new JsonSerializer();
        $serializer->configure(__DIR__ . '/resources/config_factory_object.yml');

        /** @var TestObject $object */
        $object = $serializer->deserialize($json, TestObject::class);

        $this->assertNotNull($object);
        $this->assertNotNull($object->getFactoryTestObject());
        $this->assertTrue($object->getFactoryTestObject() instanceof FactoryTestObject);
        $this->assertEquals('foobar', $object->getFactoryTestObject()->toString());
    }

    public function testInvalidFactoryMethod()
    {
        $this->expectException(DeserializingException::class);
        $this->expectExceptionMessage("No such factory method 'fromData'");

        $json = json_encode(array(
            'factoryTestObject' => 'foobar'
        ));

        $serializer = new JsonSerializer();
        $serializer->configure(__DIR__ . '/resources/config_invalid_factory_method.yml');

        $serializer->deserialize($json, TestObject::class);
    }

    public function testNonStaticFactoryMethod()
    {
        $this->expectException(DeserializingException::class);
        $this->expectExceptionMessage("Factory method 'nonStaticFactory' must be static");

        $json = json_encode(array(
            'factoryTestObject' => 'foobar'
        ));

        $serializer = new JsonSerializer();
        $serializer->configure(__DIR__ . '/resources/config_non_static_factory_method.yml');

        $serializer->deserialize($json, TestObject::class);
    }

    public function testParentObjectDeserialization()
    {
        $json = json_encode(array(
            'parentStringProperty' => 'parentFoo'
        ));

        $serializer = new JsonSerializer();

        /** @var TestObject $object */
        $object = $serializer->deserialize($json, TestObject::class);

        $this->assertNotNull($object);
        $this->assertEquals('parentFoo', $object->getParentStringProperty());
    }
}
