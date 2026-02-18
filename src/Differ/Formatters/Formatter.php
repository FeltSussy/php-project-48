<?php

namespace Differ\Differ\Formatters;

function formString(array $array): string
{
    $result = "{\n";
    foreach ($array as $data) {
        $type = $data['type'];
        $key = $data['key'];

        switch ($type) {
            case 'removed':
                $value = formatValue($data['value']);
                $result .= "  - {$key}: {$value}\n";
                break;

            case 'added':
                $value = formatValue($data['value']);
                $result .= "  + {$key}: {$value}\n";
                break;

            case 'unchanged':
                $value = formatValue($data['value']);
                $result .= "    {$key}: {$value}\n";
                break;

            case 'changed':
                $oldValue = formatValue($data['old']);
                $newValue = formatValue($data['new']);
                $result .= "  - {$key}: {$oldValue}\n";
                $result .= "  + {$key}: {$newValue}\n";
                break;

            default:
                throw new \InvalidArgumentException("Unknown type {$type}");
        }
    }
    return $result . "}\n";
}

function formatValue(mixed $value): string
{
    $string = $value;
    if ($value === true) {
        $string = 'true';
    }

    if ($value === false) {
        $string = 'false';
    }

    if ($value === null) {
        $string = 'null';
    }

    if (is_array($value)) {
        $string = 'array';
    }
    return $string;
}
