<?php

namespace Differ\Formatters;

use Exception;

use function Differ\Formatters\Stylish\formStylish;
use function Differ\Formatters\Plane\formPlain;

const STYLISH = 'stylish';
const PLAIN = 'plain';
const JSON = 'json';

function formString(array $diff, string $formatName)
{
    switch ($formatName) {
        case STYLISH:
            return formStylish($diff);

        case PLAIN:
            return formPlain($diff);

        default:
            throw new Exception("Unknown format");
    }
}
