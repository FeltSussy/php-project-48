<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;
use InvalidArgumentException;

const JSON = 'json';
const YAML = 'yaml';
const YML = 'yml';


function getContent(string $path): string
{
    $absolutePath = realpath($path);

    if ($absolutePath === false) {
        throw new InvalidArgumentException('Failed to get real path');
    }

    if (is_dir($absolutePath)) {
        throw new InvalidArgumentException('Path is a directory');
    }

    if (!is_file($absolutePath)) {
        throw new InvalidArgumentException('File not found');
    }

    if (!is_readable($absolutePath)) {
        throw new InvalidArgumentException('File is not readable');
    }

    $content = file_get_contents($absolutePath);

    if ($content === false) {
        throw new InvalidArgumentException('Failed to read file');
    }

    return $content;
}

function parseContentByFormat(string $content, string $format): array
{
    switch ($format) {
        case JSON:
            return json_decode($content, true);

        case YAML:
            return Yaml::parse($content);

        case YML:
            return Yaml::parse($content);

        default:
            throw new InvalidArgumentException("Unsupported format: {$format}");
    }
}

function getFileFormat(string $path): string
{
    $pos = strrpos($path, '.');

    if ($pos === false) {
        throw new InvalidArgumentException("File has no extension: {$path}");
    }

    return substr($path, $pos + 1);
}
