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

        return array_map(function ($key) use ($first, $second, $buildDiffTree) {
            $inFirst = array_key_exists($key, $first);
            $inSecond = array_key_exists($key, $second);

            if (!$inSecond) {
                $result = [
                    'key' => $key,
                    'type' => REMOVED,
                    'value' => $first[$key],
                ];
            } elseif (!$inFirst) {
                $result = [
                    'key' => $key,
                    'type' => ADDED,
                    'value' => $second[$key],
                ];
            } else {
                $firstValue = $first[$key];
                $secondValue = $second[$key];

                if ($firstValue === $secondValue) {
                    $result = [
                        'key' => $key,
                        'type' => UNCHANGED,
                        'value' => $firstValue,
                    ];
                } elseif (is_array($firstValue) && is_array($secondValue)) {
                    $result = [
                        'key' => $key,
                        'type' => NESTED,
                        'children' => $buildDiffTree($firstValue, $secondValue),
                    ];
                } else {
                    $result = [
                        'key' => $key,
                        'type' => UPDATED,
                        'old' => $firstValue,
                        'new' => $secondValue,
                    ];
                }
            }

            return $result;
        }, $allKeysSorted);
    };

    return format($buildDiffTree($firstParsedContent, $secondParsedContent), $formatName);
}
