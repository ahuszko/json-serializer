<?php

namespace Ahuszko\JsonSerializer\Repository;

use Ahuszko\JsonSerializer\Contract\Resolver;
use Ahuszko\JsonSerializer\Contract\Serializer;
use Ahuszko\JsonSerializer\SimpleResolver;
use InvalidArgumentException;

class SerializerRepository {

    /**
     * @var string[]
     */
    private array $types = [];

    /**
     * @var array<string, Serializer>
     */
    private array $map = [];

    /**
     * @var array<string, string>
     */
    private array $cache = [];

    private Resolver $resolver;

    public function __construct(Resolver $resolver = null) {
        $this->resolver = $resolver ?: new SimpleResolver();
    }

    public function register(string $type, Serializer|string $serializer): void {
        if ($this->isTypeValid($type) && $this->isSerializerValid($serializer)) {
            $this->map[$type] = $serializer;
            $this->clearCache();
            $this->sortTypes();
        } else {
            throw new InvalidArgumentException();
        }
    }

    private function isTypeValid($type): bool {
        return class_exists($type) || interface_exists($type);
    }

    private function isSerializerValid($serializer): bool {
        return is_a($serializer, Serializer::class, true);
    }

    private function clearCache() {
        $this->cache = [];
    }

    private function sortTypes() {
        $this->types = array_keys($this->map);

        usort($this->types, function ($a, $b) {
            if ($a === $b) {
                return 0;
            }

            return is_a($a, $b, true) ? -1 : 1;
        });
    }

    public function find(object $object): Serializer|null {
        $resolver = $this->resolver;
        $class = get_class($object);

        if (array_key_exists($class, $this->map)) {
            return $resolver->resolve($this->map[$class]);
        }

        if (array_key_exists($class, $this->cache)) {
            return $resolver->resolve($this->map[$this->cache[$class]]);
        }

        foreach ($this->types as $type) {
            if (is_a($object, $type, false)) {
                $this->cache[$class] = $type;

                return $resolver->resolve($this->map[$type]);
            }
        }

        return null;
    }
}
