<?php

namespace App\Helper;

class ArrayHelper
{
    /**
     * removes null entries from nested arrays
     * @template T
     * @param array<string, T> $array
     *
     * @return array<string, T>
     */
    public static function stripNullEntries(array $array): array
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $entries = self::stripNullEntries($value);
                if (count($entries) > 0) {
                    $array[$key] = $entries;
                } else {
                    unset($array[$key]);
                }
            }
            if ($array[$key] === null) {
                unset($array[$key]);
            }
        }
        return $array;
    }
}
