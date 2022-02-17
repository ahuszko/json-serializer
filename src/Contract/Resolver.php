<?php

namespace Ahuszko\JsonSerializer\Contract;

interface Resolver {
    function resolve(string|Serializer|Deserializer $handler): Serializer|Deserializer;
}
