<?php

use Ahuszko\JsonSerializer\Contract\Encoder;
use Ahuszko\JsonSerializer\ObjectMapper;
use Ahuszko\JsonSerializer\Serializer\CallableSerializer;
use Test\TestDeserializer;
use Test\TestSerializer;
use Test\TestSubject;

require_once __DIR__ . '/../vendor/autoload.php';

$mapper = new ObjectMapper();
$mapper->registerEncoder(TestSubject::class, TestSerializer::class);
$mapper->registerDecoder(TestSubject::class, TestDeserializer::class);

$instance = new TestSubject("hello");
$subject = [$instance, $instance];

// full control over on how to serialize an object structure to JSON
var_dump($mapper->serialize($subject));

// and deserialize from JSON
var_dump($mapper->deserialize('{"key":"value"}', TestSubject::class));

// perfect for presenting errors
$mapper->registerEncoder(
    InvalidArgumentException::class,
    CallableSerializer::from(function (InvalidArgumentException $exception, Encoder $encoder) {
        return [
            "message" => $exception->getMessage(),
            "code"    => $exception->getCode()
            // conditionally add more details in development environment
        ];
    })
);

var_dump($mapper->serialize(new InvalidArgumentException("error", 1234)));

// native json_encode sees only public properties
// or the jsonSerialize method if JsonSerializable is implemented
var_dump(json_encode($subject));
