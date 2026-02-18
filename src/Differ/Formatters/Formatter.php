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
                $value = formatToString($data['value']);
                $result .= "  - {$key}: {$value}\n";
                break;

            case 'added':
                $value = formatToString($data['value']);
                $result .= "  + {$key}: {$value}\n";
                break;

            case 'unchanged':
                $value = formatToString($data['value']);
                $result .= "    {$key}: {$value}\n";
                break;

            case 'changed':
                $oldValue = formatToString($data['old']);
                $newValue = formatToString($data['new']);
                $result .= "  - {$key}: {$oldValue}\n";
                $result .= "  + {$key}: {$newValue}\n";
                break;

            default:
                throw new \Exception("Unknown type {$type}");
        }
    }
    return $result . "}\n";
}

function formatToString(mixed $value): string
{
    if ($value === true) {
        return 'true';
    } elseif ($value === false) {
        return 'false';
    } elseif ($value === null) {
        return 'null';
    } elseif (is_array($value)) {
        return 'array';
    } else {
        return (string) $value;
    }
}
