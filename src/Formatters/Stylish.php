<?php

namespace Differ\Formatters\Stylish;

use InvalidArgumentException;

use const Differ\Constants\{
    REMOVED,
    ADDED,
    UNCHANGED,
    UPDATED,
    NESTED,
};

const INDENT_SYMBOL = ' ';
const INDENT_COUNT = 4;
const SPECIAL_CHAR_LENGTH = 2;
const SPECIAL_CHARS = [
    REMOVED => '- ',
    ADDED => '+ ',
    UNCHANGED => '  ',
    NESTED => '  ',
];

function renderStylish(array $diff): string
{
    $rendered = renderDiff($diff);
    return "{$rendered}\n}\n";
}

function renderDiff(array $diff, int $depth = 1): string
{
    $indent = str_repeat(INDENT_SYMBOL, $depth * INDENT_COUNT - SPECIAL_CHAR_LENGTH);

    $lines = array_map(function ($node) use ($depth, $indent): string {
        $type = $node['type'];
        $key = $node['key'];

        if ($type === REMOVED || $type === ADDED || $type === UNCHANGED) {
            $value = renderValue($node['value'], $depth + 1);
            $specialChr = match ($type) {
                REMOVED => SPECIAL_CHARS[REMOVED],
                ADDED => SPECIAL_CHARS[ADDED],
                UNCHANGED => SPECIAL_CHARS[UNCHANGED],
            };

            return "{$indent}{$specialChr}{$key}: {$value}";
        }

        if ($type === UPDATED) {
            $oldValue = renderValue($node['old'], $depth + 1);
            $newValue = renderValue($node['new'], $depth + 1);
            $specialCheRemoved = SPECIAL_CHARS[REMOVED];
            $specialChrAdded = SPECIAL_CHARS[ADDED];
            return "{$indent}{$specialCheRemoved}{$key}: {$oldValue}\n{$indent}{$specialChrAdded}{$key}: {$newValue}";
        }

        if ($type === NESTED) {
            $children = $node['children'] ?? [];
            $specialChr = SPECIAL_CHARS[NESTED];
            $recursion = renderDiff($children, $depth + 1);
            return "{$indent}{$specialChr}{$key}: {$recursion}\n{$indent}  }";
        }

        return throw new InvalidArgumentException("Invalid node type '{$type}'");
    }, $diff);

    return implode("\n", ["{", ...$lines]);
}

function renderValue(mixed $value, int $depth): string
{
    if (is_array($value)) {
        $currentIndent = str_repeat(INDENT_SYMBOL, $depth * INDENT_COUNT);
        $closingIndent = str_repeat(INDENT_SYMBOL, ($depth - 1) * INDENT_COUNT);

        $lines = array_map(
            function ($key, $value) use ($currentIndent, $depth) {
                $recursion = renderValue($value, $depth + 1);
                return "{$currentIndent}{$key}: {$recursion}";
            },
            array_keys($value),
            $value
        );

        $imploded = implode("\n", $lines);

        return "{\n{$imploded}\n{$closingIndent}}";
    }

    return toString($value);
}

function toString(mixed $value): string
{
    if ($value === null) {
        return 'null';
    }

    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    return (string) $value;
}
