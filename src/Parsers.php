<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;
use RuntimeException;
use JsonException;

const JSON = 'json';
const YAML = 'yaml';
const YML = 'yml';


function getContent(string $path): array
{
    $absolutePath = getPath($path);
    $format = getFileFormat($path);
    if (is_dir($absolutePath)) {
        throw new RuntimeException('Path is a directory');
    } elseif (!is_file($absolutePath)) {
        throw new RuntimeException('File not found');
    } elseif (!is_readable($absolutePath)) {
        throw new RuntimeException('File is not readable');
    }
    return parseFileByFormat($format, $absolutePath);
}

function parseFileByFormat(string $format, string $path): array
{
    $contentString = file_get_contents($path);
    if ($format === JSON) {
        $contentArray = json_decode($contentString, associative: true);
    } elseif ($format === YAML || $format === YML) {
        $contentArray = Yaml::parse($contentString);
    }

    if (!is_array($contentArray)) {
        throw new JsonException('Invalid JSON');
    }
    return $contentArray;
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

function getFileFormat(string $path): string
{
    return substr($path, strrpos($path, '.') + 1);
}
