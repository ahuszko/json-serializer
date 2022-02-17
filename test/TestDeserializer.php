<?php

namespace Test;

use Ahuszko\JsonSerializer\Contract\Decoder;
use Ahuszko\JsonSerializer\Contract\Deserializer;

class TestDeserializer implements Deserializer {
    function deserialize(mixed $subject, Decoder $decoder): TestSubject {

        // TODO schema validation
        return new TestSubject($subject->key);
    }
}
