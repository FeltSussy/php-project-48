<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\CoversFunction;

use function Differ\Differ\Formatters\formString;
use function Differ\Differ\Formatters\formatValue;

#[CoversFunction('Differ\Differ\Formatters\formString')]
class FormatterTest extends TestCase
{
    #[DataProvider('formStringProvider')]
    public function testFormString(string $expected, array $argument): void
    {
        $this->assertEquals($expected, formString($argument));
    }

    public static function formStringProvider(): array
    {
        return [
            [
                "{\n  + port: true\n}\n",
                [['key' => 'port', 'type' => 'added', 'value' => true]]
            ],
            [
                "{\n    ip: 192.100.10.10\n}\n",
                [['key' => 'ip', 'type' => 'unchanged', 'value' => '192.100.10.10']]
            ],
            [
                "{\n  - ttl: 1\n  + ttl: 2\n}\n",
                [['key' => 'ttl', 'type' => 'changed', 'old' => 1, 'new' => 2]]
            ],
            [
                "{\n  - ssl: null\n  + tcp: 80\n}\n",
                [['key' => 'ssl', 'type' => 'removed', 'value' => null], ['key' => 'tcp', 'type' => 'added', 'value' => 80]]
            ]
        ];
    }
}