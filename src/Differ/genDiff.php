<?php

namespace Differ\Differ;

function genDiff(string ...$paths)
{
    if (count($paths) !== 2) {
        throw new \Exception("Two paths are required");
    }

    $parsed = array_reduce($paths, function ($carry, $path) {
        $carry[] = sortByKeys(getContent($path));
        return $carry;
    }, []);

    [$first, $second] = $parsed;

    $result = "{\n";
    foreach ($first as $key => $value) {
        if ($value === true) {
            $value = "true";
        }

        if ($value === false) {
            $value = "false";
        }

        if (!array_key_exists($key, $second)) {
            $result .= "  - {$key}: {$value}\n";
        }

        if (array_key_exists($key, $second)) {
            if ($value === $second[$key]) {
                $result .= "    {$key}: {$value}\n";
            }

            if ($value !== $second[$key]) {
                $result .= "  - {$key}: {$value}\n";
                $result .= "  + {$key}: {$second[$key]}\n";
            }
        }
    }

    foreach ($second as $secondKey => $secondValue) {
        if ($secondValue === true) {
            $secondValue = "true";
        }

        if ($secondValue === false) {
            $secondValue = "false";
        }

        if (!array_key_exists($secondKey, $first)) {
            $result .= "  + {$secondKey}: {$secondValue}\n";
        }
    }
    $result .= "}\n";

    return $result;
}

function isAbsolute(string $path): bool
{
    return str_starts_with($path, '/');
}

function getPath(string $path): string
{
    $relPath = getcwd() . '/' . $path;
    $truePath = isAbsolute($path) ? $path : $relPath;
    return $truePath;
}

function getContent(string $path)
{
    $content = json_decode(file_get_contents(getPath($path)), true);
    return $content;
}

function sortByKeys(array $content): array
{
    $sorted = $content;
    ksort($sorted);
    return $sorted;
}
