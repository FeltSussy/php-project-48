<?php

namespace Tests\Differ\Formatters;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\CoversFunction;

use function Differ\Formatters\formString;

#[CoversFunction('Differ\Formatters\formString')]
class FormattersTest extends TestCase
{
    #[DataProvider('formStylishProvider')]
    public function testFormString(string $expected, array $argument): void
    {
        $this->assertEquals($expected, formString($argument, 'stylish'));
    }

    public static function formStylishProvider(): array
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
                        'type' => 'updated',
                        'old' => 1, 'new' => 2
                    ]
                ]
            ],
            [
                <<<EOT
                {
                  - ssl: NULL
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
            ],
            [
                <<<EOT
                {
                    group1: {
                      - baz: bas
                      + baz: bars
                        foo: bar
                      - nest: {
                            key: value
                        }
                      + nest: str
                    }
                }

                EOT,
                [
                    [
                        'key' => 'group1',
                        'type' => 'nested',
                        'children' => [
                            [
                                'key' => 'baz',
                                'type' => 'updated',
                                'old' => 'bas',
                                'new' => 'bars',
                            ],
                            [
                                'key' => 'foo',
                                'type' => 'unchanged',
                                'value' => 'bar',
                            ],
                            [
                                'key' => 'nest',
                                'type' => 'updated',
                                'old' => [
                                    'key' => 'value',
                                ],
                                'new' => 'str',
                            ],
                        ],
                    ]
                ]
            ]
        ];
    }
}
