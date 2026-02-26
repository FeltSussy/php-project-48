<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

const JSON = 'json';
const YAML = 'yaml';
const YML = 'yml';


function getContent(string $path): array
{
    $truePath = getPath($path);
    $format = getFileFormat($truePath);
    if (is_dir($truePath)) {
        throw new \ErrorException('Path is a directory');
    } elseif (!is_file($truePath)) {
        throw new \ErrorException('File not found');
    } elseif (!is_readable($truePath)) {
        throw new \ErrorException('File is not readable');
    }
    return parseFileByFormat($format, $truePath);
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
        throw new \Exception('Invalid YAML');
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
