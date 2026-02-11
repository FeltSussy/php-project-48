<?php

namespace Hexlet\Code\Parser;

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

function parse(...$paths)
{
    foreach ($paths as $path) {
        $content = json_decode(file_get_contents($path));
        echo print_r($content);
    }
}
