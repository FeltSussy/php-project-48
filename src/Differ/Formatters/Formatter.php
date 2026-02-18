<?php

namespace Differ\Differ\Formatters;

function formString(array $array): string
{
    $string = "{\n";
    foreach ($array as $element => $data) {
        $type = $data['type'];
        $key = $data['key'];

        switch ($type) {
            case 'removed':
                $value = formatToString($data['value']);
                $string .= "  - {$key}: {$value}\n";
                break;

            case 'added':
                $value = formatToString($data['value']);
                $string .= "  + {$key}: {$value}\n";
                break;

            case 'unchanged':
                $value = formatToString($data['value']);
                $string .= "    {$key}: {$value}\n";
                break;

            case 'changed':
                $oldValue = formatToString($data['old']);
                $newValue = formatToString($data['new']);
                $string .= "  - {$key}: {$oldValue}\n";
                $string .= "  + {$key}: {$newValue}\n";
                break;
        }
    }
    return $string . "}\n";
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
    } else return (string) $value;
}
