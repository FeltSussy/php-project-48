<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;
use InvalidArgumentException;

const JSON = 'json';
const YAML = 'yaml';
const YML = 'yml';

function parse(string $path): array
{
    $content = getFileContent($path);
    $format = getFileFormat($path);

    switch ($format) {
        case JSON:
            return json_decode($content, true);

        case YAML:
        case YML:
            return Yaml::parse($content);

        default:
            throw new InvalidArgumentException("Unsupported format: {$format}");
    }
}

function getFileContent(string $path): string
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

    $fileContent = file_get_contents($absolutePath);

    if ($fileContent === false) {
        throw new InvalidArgumentException('Failed to read file');
    }

    return $fileContent;
}

function getFileFormat(string $path): string
{
    $pos = strrpos($path, '.');

    if ($pos === false) {
        throw new InvalidArgumentException("File has no extension: {$path}");
    }

    return substr($path, $pos + 1);
}
