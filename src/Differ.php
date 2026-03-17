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

        $result = [];

        foreach ($allKeys as $key) {
            $inFirst = array_key_exists($key, $first);
            $inSecond = array_key_exists($key, $second);

            if (!$inSecond) {
                $result[] = [
                    'key' => $key,
                    'type' => REMOVED,
                    'value' => $first[$key],
                ];
                continue;
            }

            if (!$inFirst) {
                $result[] = [
                    'key' => $key,
                    'type' => ADDED,
                    'value' => $second[$key],
                ];
                continue;
            }

            $firstValue = $first[$key];
            $secondValue = $second[$key];

            if ($firstValue === $secondValue) {
                $result[] = [
                    'key' => $key,
                    'type' => UNCHANGED,
                    'value' => $firstValue,
                ];
                continue;
            }

            if (is_array($firstValue) && is_array($secondValue)) {
                $result[] = [
                    'key' => $key,
                    'type' => NESTED,
                    'children' => $buildDiffTree($firstValue, $secondValue),
                ];
                continue;
            }

            $result[] = [
                'key' => $key,
                'type' => UPDATED,
                'old' => $firstValue,
                'new' => $secondValue,
            ];
        }

        return $result;
    };

    return formString($buildDiffTree($firstData, $secondData), $formatName);
}
