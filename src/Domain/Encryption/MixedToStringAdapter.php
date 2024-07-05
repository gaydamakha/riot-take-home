<?php

declare(strict_types=1);

namespace Gaydamakha\RiotTakeHome\Domain\Encryption;

class MixedToStringAdapter
{
    public static function from(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        } elseif (is_scalar($value)) { // int, float or string
            return (string)$value;
        } elseif ($value === null) {
            return 'null';
        } else { // array or stdClass
            return json_encode($value);
        }
    }

    public static function to(string $value): mixed
    {
        if ($value === 'null') {
            return null;
        } elseif ($value === 'true') {
            return true;
        } elseif ($value === 'false') {
            return false;
        } elseif (is_numeric($value)) {
            return str_contains($value, '.') ? (float)$value : (int)$value;
        } elseif (($json = json_decode($value, associative: true)) === null) { //json_decode does not convert strings to json strings
            return $value;
        } else {
            return $json;
        }
    }
}
