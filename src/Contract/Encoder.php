<?php

namespace Ahuszko\JsonSerializer\Contract;

interface Encoder {
    function encode(mixed $subject): mixed;
}
