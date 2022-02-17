<?php

namespace Ahuszko\JsonSerializer\Contract;

interface Deserializer {
    function deserialize(mixed $subject, Decoder $decoder): mixed;
}
