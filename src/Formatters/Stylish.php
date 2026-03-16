<?php

namespace Differ\Formatters\Stylish;

use Exception;

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

            if ($type === REMOVED) {
                $value = $renderValue($node['value'], $depth + 1);
                return "{$indent}" . SPECIAL_CHARS[REMOVED] . "{$key}: {$value}";
            }

            if ($type === ADDED) {
                $value = $renderValue($node['value'], $depth + 1);
                return "{$indent}" . SPECIAL_CHARS[ADDED] . "{$key}: {$value}";
            }

            if ($type === UNCHANGED) {
                $value = $renderValue($node['value'], $depth + 1);
                return "{$indent}" . SPECIAL_CHARS[UNCHANGED] . "{$key}: {$value}";
            }

            if ($type === UPDATED) {
                $oldValue = $renderValue($node['old'], $depth + 1);
                $newValue = $renderValue($node['new'], $depth + 1);
                return "{$indent}" . SPECIAL_CHARS[REMOVED] . "{$key}: {$oldValue}\n{$indent}"
                    . SPECIAL_CHARS[ADDED] . "{$key}: {$newValue}";
            }

            if ($type === NESTED) {
                $children = $node['children'] ?? [];
                return "{$indent}" . SPECIAL_CHARS[NESTED]
                    . "{$key}: {$renderDiff($children, $depth + 1)}\n{$indent}  }";
            }

            return throw new Exception("Invalid type");
        }, $nodes);
        return implode("\n", ["{", ...$lines]);
    };
    return $renderDiff($diff) . "\n}\n";
}

function toString(mixed $value): string
{
    $result = trim(var_export($value, true), "'");
    return $result === null ? throw new Exception("'toString' returned null, string expected") : $result;
}
