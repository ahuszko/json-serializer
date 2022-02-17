<?php

namespace Ahuszko\JsonSerializer\Contract;

interface Serializer {
    function serialize(mixed $subject, Encoder $encoder): mixed;
}
