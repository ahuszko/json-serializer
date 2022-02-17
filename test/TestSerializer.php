<?php

namespace Test;

use Ahuszko\JsonSerializer\Contract\Encoder;
use Ahuszko\JsonSerializer\Contract\Serializer;

class TestSerializer implements Serializer {

    /**
     * @param TestSubject $subject
     * @param Encoder $encoder
     * @return array
     */
    function serialize(mixed $subject, Encoder $encoder): array {
        return [
            "key" => $subject->getValue()
        ];
    }
}
