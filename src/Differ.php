<?php

namespace Differ\Differ;

use function Differ\Parsers\getContent;
use function Differ\Parsers\getFileFormat;
use function Differ\Parsers\parseContentByFormat;
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
    $firstFileContent = getContent($firstPath);
    $secondFileContent = getContent($secondPath);

    $firstFileFormat = getFileFormat($firstPath);
    $secondFileFormat = getFileFormat($secondPath);

    $firstParsedContent = parseContentByFormat($firstFileContent, $firstFileFormat);
    $secondParsedContent = parseContentByFormat($secondFileContent, $secondFileFormat);

    $buildDiffTree = function ($first, $second) use (&$buildDiffTree) {
        $allKeys = array_unique(array_merge(array_keys($first), array_keys($second)));
        $allKeysSorted = array_values(sortBy($allKeys, fn ($key) => $key));

        $diff = array_map(function ($key) use ($first, $second, $buildDiffTree) {
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
                    'children' => $buildDiffTree($firstValue, $secondValue),
                ];
            }

            return [
                'key' => $key,
                'type' => UPDATED,
                'old' => $firstValue,
                'new' => $secondValue,
            ];
        }, $allKeysSorted);

        return $diff;
    };

    return format($buildDiffTree($firstParsedContent, $secondParsedContent), $formatName);
}
