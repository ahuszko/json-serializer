<?php

namespace Ahuszko\JsonSerializer\Repository;

use Ahuszko\JsonSerializer\Contract\Deserializer;
use Ahuszko\JsonSerializer\Contract\Resolver;
use Ahuszko\JsonSerializer\SimpleResolver;
use InvalidArgumentException;

class DeserializerRepository {

    private array $decoders = [];

    private Resolver $resolver;

    public function __construct(Resolver $resolver = null) {
        $this->resolver = $resolver ?: new SimpleResolver();
    }

    public function register(string $type, Deserializer|string $deserializer) {
        if ($this->isTypeValid($type) && $this->isDeserializerValid($deserializer)) {
            $this->decoders[$type] = $deserializer;
        } else {
            throw new InvalidArgumentException();
        }
    }

    private function isTypeValid($type): bool {
        return class_exists($type) || interface_exists($type);
    }

    private function isDeserializerValid($deserializer): bool {
        return is_a($deserializer, Deserializer::class, true);
    }

    public function find(string $type): Deserializer|null {
        if (array_key_exists($type, $this->decoders)) {
            return $this->resolver->resolve($this->decoders[$type]);
        }

        return null;
    }
}
