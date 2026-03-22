<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Differ\Parsers\getFileContent;
use function Differ\Parsers\getFileFormat;
use function Differ\Formatters\format;
use function Funct\Collection\sortBy;

use const Differ\Constants\{
    REMOVED,
    ADDED,
    UNCHANGED,
    UPDATED,
    NESTED
};

function genDiff(string $firstPath, string $secondPath, string $formatName = 'stylish'): string
{
    $firstParsed = parse(getFileContent($firstPath), getFileFormat($firstPath));
    $secondParsed = parse(getFileContent($secondPath), getFileFormat($secondPath));

    $diffTree = buildDiffTree($firstParsed, $secondParsed);

    return format($diffTree, $formatName);
}

function buildDiffTree(array $first, array $second): array
{
    $allKeys = array_unique(array_merge(array_keys($first), array_keys($second)));
    $allKeysSorted = array_values(sortBy($allKeys, fn ($key) => $key));

    return array_map(function ($key) use ($first, $second) {

        $inFirst = array_key_exists($key, $first);
        $inSecond = array_key_exists($key, $second);

        if (!$inSecond) {
            return [
                'key' => $key,
                'type' => REMOVED,
                'value' => $first[$key],
            ];
        }

        if (!$inFirst) {
            return [
                'key' => $key,
                'type' => ADDED,
                'value' => $second[$key],
            ];
        }

        $firstValue = $first[$key];
        $secondValue = $second[$key];

        if ($firstValue === $secondValue) {
            return [
                'key' => $key,
                'type' => UNCHANGED,
                'value' => $firstValue,
            ];
        }

        if (is_array($firstValue) && is_array($secondValue)) {
            return [
                'key' => $key,
                'type' => NESTED,
                'children' => buildDiffTree($firstValue, $secondValue),
            ];
        }

        return [
            'key' => $key,
            'type' => UPDATED,
            'old' => $firstValue,
            'new' => $secondValue,
        ];
    }, $allKeysSorted);
}
