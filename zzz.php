<?php

$data = [
    'hello' => 'world',
    'is' => true,
    'nested1' => ['count1' => ['count' => 1]],
    'nested2' => ['count2' => 2],
    'nested3' => ['count3' => ['count' => 3]]
];

function stringify ($data, $replacer = ' ', $spacesCount = 1)
{
    $space = str_repeat($replacer, $spacesCount);
    return form($data, 0, $space);
}

function form($value, $depth, $space) {
    if (is_string($value)) {
        return $value;
    }
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if ($value === null) {
        return 'null';
    }
    if (is_int($value) || is_float($value)) {
        return (string)$value;
    }

    if (is_array($value)) {
        $result = "{\n";
        $lines = [];

        $spaceCurrent = str_repeat($space, $depth);
        $spaceNext = str_repeat($space, $depth + 1);

        foreach ($value as $key => $val) {
            $lines[] = "{$spaceNext}{$key}: " . form($val, $depth + 1, $space);
        }

        $result .= implode("\n", $lines);
        $result .= "\n{$spaceCurrent}}";

        return $result;
    }

    return (string) $value;
}

echo stringify($data);