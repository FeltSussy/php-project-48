<?php

namespace Differ\Formatters\Stylish;

use InvalidArgumentException;

use const Differ\Constants\{
    REMOVED,
    ADDED,
    UNCHANGED,
    UPDATED,
    NESTED
};

const INDENT_SYMBOL = ' ';
const INDENT_COUNT = 4;
const SPECIAL_CHAR_LENGTH = 2;
const SPECIAL_CHARS = [
    REMOVED => '- ',
    ADDED => '+ ',
    UNCHANGED => '  ',
    NESTED => '  '
];

function renderStylish(array $diff): string
{
    return sprintf("%s\n}\n", renderDiff($diff));
}

function renderDiff(array $diff, int $depth = 1): string
{
    $indent = str_repeat(INDENT_SYMBOL, $depth * INDENT_COUNT - SPECIAL_CHAR_LENGTH);

    $lines = array_map(function ($node) use ($depth, $indent): string {
        $type = $node['type'];
        $key = $node['key'];

        return match (true) {
            $type === REMOVED => sprintf(
                "%s%s%s: %s",
                $indent,
                SPECIAL_CHARS[REMOVED],
                $key,
                renderValue($node['value'], $depth + 1)
            ),

            $type === ADDED => sprintf(
                "%s%s%s: %s",
                $indent,
                SPECIAL_CHARS[ADDED],
                $key,
                renderValue($node['value'], $depth + 1)
            ),

            $type === UNCHANGED => sprintf(
                "%s%s%s: %s",
                $indent,
                SPECIAL_CHARS[UNCHANGED],
                $key,
                renderValue($node['value'], $depth + 1)
            ),

            $type === UPDATED => sprintf(
                "%s%s%s: %s\n%s%s%s: %s",
                $indent,
                SPECIAL_CHARS[REMOVED],
                $key,
                renderValue($node['old'], $depth + 1),
                $indent,
                SPECIAL_CHARS[ADDED],
                $key,
                renderValue($node['new'], $depth + 1)
            ),

            $type === NESTED => sprintf(
                "%s%s%s: %s\n%s  }",
                $indent,
                SPECIAL_CHARS[NESTED],
                $key,
                renderDiff($node['children'] ?? [], $depth + 1),
                $indent
            ),

            default => throw new InvalidArgumentException('Invalid node type'),
        };
    }, $diff);

    return implode("\n", ["{", ...$lines]);
}

function renderValue(mixed $value, int $depth): string
{
    if (!is_array($value)) {
        return toString($value);
    }

    $currentIndent = str_repeat(INDENT_SYMBOL, $depth * INDENT_COUNT);
    $closingIndent = str_repeat(INDENT_SYMBOL, ($depth - 1) * INDENT_COUNT);

    $lines = array_map(
        fn($key, $value) => sprintf(
            "%s%s: %s",
            $currentIndent,
            $key,
            renderValue($value, $depth + 1)
        ),
        array_keys($value),
        $value
    );

    return sprintf(
        "{\n%s\n%s}",
        implode("\n", $lines),
        $closingIndent
    );
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
