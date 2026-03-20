<?php

namespace Differ\Formatters;

use InvalidArgumentException;

use function Differ\Formatters\Stylish\renderStylish;
use function Differ\Formatters\Plain\renderPlain;
use function Differ\Formatters\Json\renderJson;

const STYLISH = 'stylish';
const PLAIN = 'plain';
const JSON = 'json';

function format(array $diff, string $formatName): string
{
    switch ($formatName) {
        case STYLISH:
            return renderStylish($diff);

        case PLAIN:
            return renderPlain($diff);

        case JSON:
            return renderJson($diff);

        default:
            throw new InvalidArgumentException("Unknown format");
    }
}
