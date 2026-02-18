<?php

namespace Differ\Differ;

use function Funct\Collection\sortBy;
use function Differ\Differ\Formatters\formString;

function genDiff(string $firstPath, string $secondPath)
{
    $first = getContent($firstPath);
    $second = getContent($secondPath);

    $allKeys = array_merge(array_keys($first), array_keys($second));
    $normalizedKeys = sortBy(array_unique($allKeys), fn($value) => $value);

    $diffDetailed = [];
    foreach ($normalizedKeys as $key) {
        $inFirst = \array_key_exists($key, $first);
        $inSecond = \array_key_exists($key, $second);

        if ($inFirst && !$inSecond) {
            $diffDetailed[] = ['key' => $key, 'type' => 'removed', 'value' => $first[$key]];
        } elseif (!$inFirst && $inSecond) {
            $diffDetailed[] = ['key' => $key, 'type' => 'added', 'value' => $second[$key]];
        } else {
            if ($first[$key] === $second[$key]) {
                $diffDetailed[] = ['key' => $key, 'type' => 'unchanged', 'value' => $first[$key]];
            } else {
                $diffDetailed[] = ['key' => $key, 'type' => 'changed', 'old' => $first[$key], 'new' => $second[$key]];
            }
        }
    }
    return formString($diffDetailed);
}

function isAbsolute(string $path): bool
{
    return str_starts_with($path, '/');
}

function getPath(string $path): string
{
    $relPath = getcwd() . '/' . $path;
    return isAbsolute($path) ? $path : $relPath;
}

function getContent(string $path): array
{
    return json_decode(file_get_contents(getPath($path)), true);
}
