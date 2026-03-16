<?php

namespace Differ\Formatters\Json;

use JsonException;

function formJson(array $diff): string
{
    return json_encode($diff, JSON_THROW_ON_ERROR) . PHP_EOL;
}
