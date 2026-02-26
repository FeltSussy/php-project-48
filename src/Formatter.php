<?php

namespace Differ\Formatter;

const REMOVED = 'removed';
const ADDED = 'added';
const UNCHANGED = 'unchanged';
const CHANGED = 'changed';

function formString(array $array): string
{
    $result = "{\n";
    foreach ($array as $data) {
        $type = $data['type'];
        $key = $data['key'];

        switch ($type) {
            case REMOVED:
                $value = formatValue($data['value']);
                $result .= "  - {$key}: {$value}\n";
                break;

            case ADDED:
                $value = formatValue($data['value']);
                $result .= "  + {$key}: {$value}\n";
                break;

            case UNCHANGED:
                $value = formatValue($data['value']);
                $result .= "    {$key}: {$value}\n";
                break;

            case CHANGED:
                $oldValue = formatValue($data['old value']);
                $newValue = formatValue($data['new value']);
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
