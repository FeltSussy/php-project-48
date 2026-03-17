<?php

namespace Differ\Formatters\Stylish;

use InvalidArgumentException;

const INDENT_SYMBOL = ' ';
const INDENT_COUNT = 4;
const REMOVED = 'removed';
const ADDED = 'added';
const UNCHANGED = 'unchanged';
const UPDATED = 'updated';
const NESTED = 'nested';
const SPECIAL_CHAR_LENGTH = 2;
const SPECIAL_CHARS = [
    REMOVED => '- ',
    ADDED => '+ ',
    UNCHANGED => '  ',
    NESTED => '  '
];

function formStylish(array $diff): string
{
    $renderValue = function ($value, $depth) use (&$renderValue): string {
        if (!is_array($value)) {
            return toString($value);
        }

        $currentIndent = str_repeat(INDENT_SYMBOL, $depth * INDENT_COUNT);
        $closingIndent = str_repeat(INDENT_SYMBOL, ($depth - 1) * INDENT_COUNT);

        $lines = array_map(
            fn($key, $value) => "{$currentIndent}{$key}: " . $renderValue($value, $depth + 1),
            array_keys($value),
            $value
        );

        return "{\n" . implode("\n", $lines) . "\n{$closingIndent}}";
    };

    $renderDiff = function ($nodes, $depth = 1) use (&$renderDiff, $renderValue): string {
        $indent = str_repeat(INDENT_SYMBOL, $depth * INDENT_COUNT - SPECIAL_CHAR_LENGTH);

        $lines = array_map(function ($node) use ($renderDiff, $depth, $indent, $renderValue): string {
            $type = $node['type'];
            $key = $node['key'];

            $result = '';

            if ($type === REMOVED) {
                $value = $renderValue($node['value'], $depth + 1);
                $result = "{$indent}" . SPECIAL_CHARS[REMOVED] . "{$key}: {$value}";
            } elseif ($type === ADDED) {
                $value = $renderValue($node['value'], $depth + 1);
                $result = "{$indent}" . SPECIAL_CHARS[ADDED] . "{$key}: {$value}";
            } elseif ($type === UNCHANGED) {
                $value = $renderValue($node['value'], $depth + 1);
                $result = "{$indent}" . SPECIAL_CHARS[UNCHANGED] . "{$key}: {$value}";
            } elseif ($type === UPDATED) {
                $oldValue = $renderValue($node['old'], $depth + 1);
                $newValue = $renderValue($node['new'], $depth + 1);

                $result = "{$indent}" . SPECIAL_CHARS[REMOVED] . "{$key}: {$oldValue}\n"
                    . "{$indent}" . SPECIAL_CHARS[ADDED] . "{$key}: {$newValue}";
            } elseif ($type === NESTED) {
                $children = $node['children'] ?? [];
                $result = "{$indent}" . SPECIAL_CHARS[NESTED]
                    . "{$key}: {$renderDiff($children, $depth + 1)}\n{$indent}  }";
            } else {
                throw new InvalidArgumentException('Invalid type');
            }

            return $result;
        }, $nodes);

        return implode("\n", ["{", ...$lines]);
    };

    return $renderDiff($diff) . "\n}\n";
}

function toString(mixed $value): string
{
    return trim(var_export($value, true), "'");
}
