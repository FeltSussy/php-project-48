<?php

namespace Differ\Differ;

use function Funct\Collection\sortBy;
use function Differ\Formatter\formString;
use function Differ\Parsers\getContent;

const REMOVED = 'removed';
const ADDED = 'added';
const UNCHANGED = 'unchanged';
const CHANGED = 'changed';

function genDiff(string $firstPath, string $secondPath): string
{
    $first = getContent($firstPath);
    $second = getContent($secondPath);

    $allKeys = array_merge(array_keys($first), array_keys($second));
    $normalizedKeys = sortBy(array_unique($allKeys), fn($value) => $value);

    $diffDetailed = [];
    foreach ($normalizedKeys as $key) {
        $inFirst = array_key_exists($key, $first);
        $inSecond = array_key_exists($key, $second);

        if ($inFirst && !$inSecond) {
            $diffDetailed[] = [
                'key' => $key, 'type' => REMOVED, 'value' => $first[$key]
            ];
        } elseif (!$inFirst && $inSecond) {
            $diffDetailed[] = [
                'key' => $key, 'type' => ADDED, 'value' => $second[$key]
            ];
        } else {
            if ($first[$key] === $second[$key]) {
                $diffDetailed[] = [
                    'key' => $key, 'type' => UNCHANGED, 'value' => $first[$key]
                ];
            } else {
                $diffDetailed[] = [
                    'key' => $key, 'type' => CHANGED, 'old value' => $first[$key], 'new value' => $second[$key]
                ];
            }
        }
    }
    return formString($diffDetailed);
}
