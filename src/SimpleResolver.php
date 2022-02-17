<?php

namespace Ahuszko\JsonSerializer;

use Ahuszko\JsonSerializer\Contract\Deserializer;
use Ahuszko\JsonSerializer\Contract\Resolver;
use Ahuszko\JsonSerializer\Contract\Serializer;

class SimpleResolver implements Resolver {
    private array $cache = [];

    public function resolve(string|Serializer|Deserializer $handler): Serializer|Deserializer {
        if (is_string($handler)) {
            if (array_key_exists($handler, $this->cache) === false) {

                // it can only resolve handlers without dependencies
                $this->cache[$handler] = new $handler;
            }

            return $this->cache[$handler];
        }

        return $handler;
    }
}
