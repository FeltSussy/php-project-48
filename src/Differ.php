<?php

namespace Differ\Differ;

use function Differ\Parsers\getContent;
use function Differ\Formatters\formString;

const REMOVED = 'removed';
const ADDED = 'added';
const UNCHANGED = 'unchanged';
const UPDATED = 'updated';
const NESTED = 'nested';

function genDiff(string $firstPath, string $secondPath, string $formatName = 'stylish')
{
    $firstData = getContent($firstPath);
    $secondData = getContent($secondPath);

    $buildDiffTree = function ($first, $second) use (&$buildDiffTree) {
        $allKeys = array_unique(array_merge(array_keys($first), array_keys($second)));
        sort($allKeys);

        return array_map(function ($key) use ($buildDiffTree, $first, $second) {
            $inFirst = array_key_exists($key, $first);
            $inSecond = array_key_exists($key, $second);

            if (!$inSecond) {
                return [
                    'key' => $key,
                    'type' => REMOVED,
                    'value' => $first[$key]
                ];
            }

            if (!$inFirst) {
                return [
                    'key' => $key,
                    'type' => ADDED,
                    'value' => $second[$key]
                ];
            }

            $firstValue = $first[$key];
            $secondValue = $second[$key];

            if ($firstValue === $secondValue) {
                return [
                    'key' => $key,
                    'type' => UNCHANGED,
                    'value' => $firstValue
                ];
            }

            if (is_array($firstValue) && is_array($secondValue)) {
                return [
                    'key' => $key,
                    'type' => NESTED,
                    'children' => $buildDiffTree($firstValue, $secondValue)
                ];
            }

            return [
                'key' => $key,
                'type' => UPDATED,
                'old' => $firstValue,
                'new' => $secondValue
            ];
        }, $allKeys);
    };

    return formString($buildDiffTree($firstData, $secondData), $formatName);
}
