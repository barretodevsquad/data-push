<?php

/**
 * create a new array inside an array
 *
 * @param array $array
 * @param string $keys    // dot notation
 * @param mixed $value    // * is a wildcard for unknown indexes
 * @param mixed $index    // set index value instead use a sequential value
 * @param bool $recursive // add value in all targets of same level
 * @return void
 */
function data_push(array &$array, string $keys, mixed $value, mixed $index = false, bool $recursive = false): void
{
    $parts = explode('.', $keys);

    while (count($parts)) {
        $part = array_shift($parts);

        if (isset($array[$part]) && is_array($array[$part])) {
            $array = &$array[$part];
        } else {
            if ('*' !== $part || ! is_array($array[key($array)])) {
                throw new Exception('given key is not an array or the key does not exist');
            }

            $finish = false;

            if ($recursive && count($array) > 1) {
                $subArrays = $array;

                foreach ($subArrays as $subKey => $subValue) {
                    if (! count($parts)) {
                        $index ? $array[$subKey][$index] = $value : array_push($array[$subKey], $value);
                    } else {
                        data_push($array[$subKey], implode('.', $parts), value: $value, index: $index);
                    }
                }

                $finish = true;
            }

            $array = &$array[key($array)];

            if ($finish) {
                return;
            }

            continue;
        }
    }
    $index ? $array[$index] = $value : array_push($array, $value);
}
