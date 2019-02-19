# JsonSerializer
[![Build Status](https://travis-ci.org/MonsieurBon/json-serializer.svg?branch=master)](https://travis-ci.org/MonsieurBon/json-serializer) [![codecov](https://codecov.io/gh/MonsieurBon/json-serializer/branch/master/graph/badge.svg)](https://codecov.io/gh/MonsieurBon/json-serializer) [![Sonarcloud Status](https://sonarcloud.io/api/project_badges/measure?project=com.lapots.breed.judge:judge-rule-engine&metric=alert_status)](https://sonarcloud.io/dashboard?id=MonsieurBon_json-serializer)

A lightweight PHP JSON serializer

## Installation
```bash
composer require monsieurbon/json-serializer
```

## Usage
```PHP
$serializer = new JsonSerializer();
$serializer->configure('path/to/some/config.yml');

$json = $serializer->serialize($myObject);
$myObject = $serializer->deserialize($json, MyClass::class);
$myObject = $serializer->deserialize($json, function ($dataArray) {
    return 'My\ClassName\From\DataArray';
});
```

### Configuration
You can supply a configuration file in yaml format:
```YAML
NameSpace\Of\MyObject:
  arrayProperty: 'array'
  booleanProperty: 'boolean'
  dateProperty:
    type: 'date'
    dateFormat: 'Y-m-d\TH:i:s\Z'
  floatProperty: 'float'
  integerProperty: 'integer'
  stringProperty: 'string'
  nestedObject: 'NameSpace\Of\MyNestedObject'
  factoryObject:
    type: 'NameSpace\Of\FactoryObject'
    factoryMethod: 'fromData'
NameSpace\Of\MyNestedObject:
  dateProperty: 'date'
```

The type `date` supports an optional dateFormat property. It defaults to `d-m-Y H:i:s\Z`.
For nested objects you can define a static factory method that will be called with the data to create the object.

If you want to specify the data that will be serialized for an object, implement the standard php \JsonSerializable
interface.

## Dependencies
The following PHP extensions are required:
* ext-yaml
* ext-json

This package only handles the reflection part of (de)serializing and depends on the PHP json extension
for the actual (de)serialization.