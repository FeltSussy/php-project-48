<?php

namespace Differ\Formatters\Plain;

use function Funct\Collection\flattenAll;

const REMOVED = 'removed';
const ADDED = 'added';
const UNCHANGED = 'unchanged';
const UPDATED = 'updated';
const NESTED = 'nested';
const SPECIAL_CHAR = 2;

function formPlain(array $diff): string
{
    $renderValue = function ($value) {
        if (is_array($value)) {
            return "[complex value]";
        }
        return toString($value);
    };

    $renderDiff = function ($nodes, $path = '') use (&$renderDiff, $renderValue) {
        $lines = array_reduce($nodes, function ($acc, $node) use ($renderDiff, $path, $renderValue) {
            $type = $node['type'];
            $key = $node['key'];
            if ($type === NESTED) {
                $children = $node['children'] ?? [];
                $path .= $key . '.';
                $acc[] = $renderDiff($children, $path);
            } elseif ($type === ADDED) {
                $value = $node['value'];
                $acc[] = "Property '{$path}{$key}' was " . ADDED . " with value: {$renderValue($value)}";
            } elseif ($type === REMOVED) {
                $acc[] = "Property '{$path}{$key}' was " . REMOVED;
            } elseif ($type === UPDATED) {
                $old = $node['old'];
                $new = $node['new'];
                $acc[] = "Property '{$path}{$key}' was " . UPDATED
                    . ". From {$renderValue($old)} to {$renderValue($new)}";
            }
            return $acc;
        }, []);
        return implode("\n", flattenAll($lines));
    };

    return $renderDiff($diff) . "\n";
}

function toString(mixed $value): string
{
    if ($value === null) {
        return 'null';
    }

    return var_export($value, true);
}
