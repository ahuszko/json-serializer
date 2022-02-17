<?php

namespace Ahuszko\JsonSerializer\Serializer;

use Ahuszko\JsonSerializer\Contract\Encoder;
use Ahuszko\JsonSerializer\Contract\Serializer;

final class CallableSerializer implements Serializer {

    /**
     * @var callable
     */
    private $callable;

    /**
     * @example
     *  ```php
     *  CallableSerializer::from(function (Exception $exception, Encoder $encoder) {
     *      return $exception->getMessage();
     *  });
     *  ```
     */
    public static function from(callable $callable): Serializer {
        return new self($callable);
    }

    private function __construct(callable $callable) {
        $this->callable = $callable;
    }

    public function serialize(mixed $subject, Encoder $encoder): mixed {
        return call_user_func($this->callable, $subject, $encoder);
    }
}
