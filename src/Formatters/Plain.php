<?php

namespace Differ\Formatters\Plain;

use function Funct\Collection\flattenAll;

use const Differ\Constants\{
    REMOVED,
    ADDED,
    UPDATED,
    NESTED
};

function renderPlain(array $diff): string
{
    $rendered = renderDiff($diff);
    return "{$rendered}\n";
}

function renderDiff(array $nodes, string $path = ''): string
{
    $lines = array_map(function ($node) use ($path) {
        $type = $node['type'];
        $key = $node['key'];

        if ($type === NESTED) {
            $children = $node['children'] ?? [];
            $path .= $key . '.';
            return renderDiff($children, $path);
        }

        if ($type === ADDED) {
            $value = renderValue($node['value']);
            $added = ADDED;
            return "Property '{$path}{$key}' was {$added} with value: {$value}";
        }

        if ($type === REMOVED) {
            $removed = REMOVED;
            return "Property '{$path}{$key}' was {$removed}";
        }

        if ($type === UPDATED) {
            $old = renderValue($node['old']);
            $new = renderValue($node['new']);
            $updated = UPDATED;
            return "Property '{$path}{$key}' was {$updated}. From {$old} to {$new}";
        }

        return '';
    }, $nodes);

    $flattened = flattenAll($lines);
    $withoutEmptyLines = array_filter(
        $flattened,
        static fn(string $line) => $line !== ''
    );

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
