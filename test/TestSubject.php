<?php

namespace Test;

class TestSubject {
    public string $value;

    public function __construct(string $value) {
        $this->value = $value;
    }

    public function getValue(): string {
        return $this->value;
    }
}
