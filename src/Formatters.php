<?php

namespace Differ\Formatters;

use Exception;

use function Differ\Formatters\Stylish\formStylish;
use function Differ\Formatters\Plain\formPlain;
use function Differ\Formatters\Json\formJson;

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

        case JSON:
            return formJson($diff);

        default:
            throw new Exception("Unknown format");
    }
}
