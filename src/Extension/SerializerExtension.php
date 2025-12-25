<?php

namespace App\Extension;

class SerializerExtension
{
    /**
     * @template T of \BackedEnum
     * @param class-string<T> $class
     * @param array<int|string|T>|null $values
     * @return null|array<T>
     */
    public static function unserializeEnumArray(string $class, ?array $values): ?array
    {
        if (null === $values) {
            return null;
        }

        /** @var T[] $parsedArray */
        $parsedArray = [];
        foreach ($values as $value) {
            if (is_string($value) || is_int($value)) {
                $parsedArray[] = $class::from($value);
            } else {
                $parsedArray[] = $value;
            }
        }

        return $parsedArray;
    }
}
