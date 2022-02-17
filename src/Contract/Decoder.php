<?php

namespace Ahuszko\JsonSerializer\Contract;

interface Decoder {
    function decode(mixed $subject, string $type): mixed;
}
