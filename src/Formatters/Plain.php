<?php

namespace Differ\Formatters\Plain;

use function Funct\Collection\flattenAll;

use const Differ\Constants\{
    REMOVED,
    ADDED,
    UNCHANGED,
    UPDATED,
    NESTED
};

const SPECIAL_CHAR = 2;

function renderPlain(array $diff): string
{
    return renderDiff($diff) . "\n";
}

function renderDiff(array $nodes, string $path = ''): string
{
    $lines = array_map(function ($node) use ($path) {
        $type = $node['type'];
        $key = $node['key'];
        $newPath = "{$path}{$key}.";

        return match (true) {
            $type === NESTED => sprintf(
                "%s",
                renderDiff($node['children'] ?? [], $newPath)
            ),

            $type === ADDED => sprintf(
                "Property '%s%s' was %s with value: %s",
                $path,
                $key,
                ADDED,
                renderValue($node['value'])
            ),

            $type === REMOVED => sprintf(
                "Property '%s%s' was %s",
                $path,
                $key,
                REMOVED
            ),

            $type === UPDATED => sprintf(
                "Property '%s%s' was %s. From %s to %s",
                $path,
                $key,
                UPDATED,
                renderValue($node['old']),
                renderValue($node['new'])
            ),

            default => '',
        };
    }, $nodes);

    $flattened = flattenAll($lines);
    $withoutEmptyLines = array_filter($flattened, null);

    return implode("\n", $withoutEmptyLines);
}

function renderValue(mixed $value): string
{
    if (is_array($value)) {
        return "[complex value]";
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

    return is_string($value) ? "'{$value}'" : (string) $value;
}
