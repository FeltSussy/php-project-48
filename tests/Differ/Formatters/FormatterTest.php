<?php

namespace Tests\Differ\Formatters;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\CoversFunction;

use function Differ\Differ\Formatters\formString;

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
                <<<EOT
                {
                  + port: true
                }
                
                EOT,
                [
                    [
                        'key' => 'port',
                        'type' => 'added',
                        'value' => true
                    ]
                ]
            ],
            [
                <<<EOT
                {
                    ip: 192.100.10.10
                }
                
                EOT,
                [
                    [
                        'key' => 'ip',
                        'type' => 'unchanged',
                        'value' => '192.100.10.10'
                    ]
                ]
            ],
            [
                <<<EOT
                {
                  - ttl: 1
                  + ttl: 2
                }
                
                EOT,
                [
                    [
                        'key' => 'ttl',
                        'type' => 'changed',
                        'old' => 1, 'new' => 2
                    ]
                ]
            ],
            [
                <<<EOT
                {
                  - ssl: null
                  + tcp: 80
                }
                
                EOT,
                [
                    [
                        'key' => 'ssl',
                        'type' => 'removed',
                        'value' => null
                    ],
                    [
                        'key' => 'tcp',
                        'type' => 'added',
                        'value' => 80
                    ]
                ]
            ]
        ];
    }
}
