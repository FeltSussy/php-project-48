<?php

namespace Differ\Differ\Parsers;

function getContent(string $path): array
{
    $truePath = getPath($path);
    if (is_dir($truePath)) {
        throw new \ErrorException('Path is a directory');
    } elseif (!is_file($truePath)) {
        throw new \ErrorException('File not found');
    } elseif (!is_readable($truePath)) {
        throw new \ErrorException('File is not readable');
    } else {
        $jsonContent = file_get_contents($truePath);
    }

    $content = json_decode($jsonContent, associative: true);

    if (!is_array($content)) {
        throw new \JsonException('Invalid JSON');
    }
    return $content;
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
