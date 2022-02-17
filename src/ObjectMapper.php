<?php

namespace Ahuszko\JsonSerializer;

use Ahuszko\JsonSerializer\Contract\Decoder;
use Ahuszko\JsonSerializer\Contract\Deserializer;
use Ahuszko\JsonSerializer\Contract\Encoder;
use Ahuszko\JsonSerializer\Contract\Resolver;
use Ahuszko\JsonSerializer\Contract\Serializer;
use Ahuszko\JsonSerializer\Repository\DeserializerRepository;
use Ahuszko\JsonSerializer\Repository\SerializerRepository;

class ObjectMapper implements Encoder, Decoder {
    private SerializerRepository $serializerRepository;
    private DeserializerRepository $deserializerRepository;

    public function __construct(Resolver $resolver = null) {
        $this->serializerRepository = new SerializerRepository($resolver);
        $this->deserializerRepository = new DeserializerRepository($resolver);
    }

    public function registerEncoder(string $type, Serializer|string $serializer) {
        $this->serializerRepository->register($type, $serializer);
    }

    public function registerDecoder(string $type, Deserializer|string $deserializer) {
        $this->deserializerRepository->register($type, $deserializer);
    }

    public function serialize(mixed $subject, int $flags = 0, int $depth = 512): string {
        return json_encode($this->encode($subject), $flags, $depth);
    }

    public function deserialize(string $subject, string $type): mixed {
        return $this->decode(json_decode($subject, false), $type);
    }

    public function toSimple(mixed $subject, bool $associative = false): mixed {
        return json_decode(json_encode($this->encode($subject)), $associative);
    }

    public function encode(mixed $subject): mixed {
        if (is_null($subject) || in_array(
                strtolower(gettype($subject)),
                ["integer", "double", "boolean", "string"],
                true
            )
        ) {
            return $subject;
        }

        if (is_array($subject)) {
            return array_map([$this, "encode"], $subject);
        }

        if (is_object($subject)) {
            $serializer = $this->serializerRepository->find($subject);

            if ($serializer instanceof Serializer) {
                return $serializer->serialize($subject, $this);
            }
        }

        return $subject;
    }

    public function decode(mixed $subject, string $type): mixed {
        $deserializer = $this->deserializerRepository->find($type);

        if ($deserializer instanceof Deserializer) {
            return $deserializer->deserialize($subject, $this);
        }

        return $subject;
    }
}
