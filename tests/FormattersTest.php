<?php

namespace Tests\Differ\Formatters;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\CoversFunction;

use function Differ\Formatters\formString;

#[CoversFunction('Differ\Formatters\formString')]
#[CoversFunction('Differ\Formatters\Stylish\formStylish')]
#[CoversFunction('Differ\Formatters\Plain\formPlain')]
#[CoversFunction('Differ\Formatters\Json\formJson')]
class FormattersTest extends TestCase
{
    #[DataProvider('formatterProvider')]
    public function testFormString(string $expected, array $diff, string $format): void
    {
        $this->assertEquals($expected, formString($diff, $format));
    }

    public static function formatterProvider(): iterable
    {
        yield from self::formStylishProvider();
        yield from self::formPlainProvider();
        yield from self::formJsonProvider();
    }

    public static function formStylishProvider(): iterable
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
                        'value' => true,
                    ]
                ],
                'stylish'
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
                        'value' => '192.100.10.10',
                    ]
                ],
                'stylish'
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
                        'old' => 1,
                        'new' => 2,
                    ]
                ],
                'stylish'
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
                        'value' => null,
                    ],
                    [
                        'key' => 'tcp',
                        'type' => 'added',
                        'value' => 80,
                    ]
                ],
                'stylish'
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
                ],
                'stylish'
            ]
        ];
    }

    public static function formPlainProvider(): iterable
    {
        return [
            [
                <<<EOT
                Property 'common.follow' was added with value: false
                Property 'common.setting2' was removed
                Property 'common.setting3' was updated. From true to NULL
                Property 'common.setting4' was added with value: 'blah blah'
                Property 'common.setting5' was added with value: [complex value]
                Property 'common.setting6.doge.wow' was updated. From '' to 'so much'
                Property 'common.setting6.ops' was added with value: 'vops'
                Property 'group1.baz' was updated. From 'bas' to 'bars'
                Property 'group1.nest' was updated. From [complex value] to 'str'
                Property 'group2' was removed
                Property 'group3' was added with value: [complex value]

                EOT,
                [
                    0 =>
                    [
                        'key' => 'common',
                        'type' => 'nested',
                        'children' =>
                        [
                        0 =>
                        [
                            'key' => 'follow',
                            'type' => 'added',
                            'value' => false,
                        ],
                        1 =>
                        [
                            'key' => 'setting1',
                            'type' => 'unchanged',
                            'value' => 'Value 1',
                        ],
                        2 =>
                        [
                            'key' => 'setting2',
                            'type' => 'removed',
                            'value' => 200,
                        ],
                        3 =>
                        [
                            'key' => 'setting3',
                            'type' => 'updated',
                            'old' => true,
                            'new' => null,
                        ],
                        4 =>
                        [
                            'key' => 'setting4',
                            'type' => 'added',
                            'value' => 'blah blah',
                        ],
                        5 =>
                        [
                            'key' => 'setting5',
                            'type' => 'added',
                            'value' =>
                            [
                            'key5' => 'value5',
                            ],
                        ],
                        6 =>
                        [
                            'key' => 'setting6',
                            'type' => 'nested',
                            'children' =>
                            [
                            0 =>
                            [
                                'key' => 'doge',
                                'type' => 'nested',
                                'children' =>
                                [
                                0 =>
                                [
                                    'key' => 'wow',
                                    'type' => 'updated',
                                    'old' => '',
                                    'new' => 'so much',
                                ],
                                ],
                            ],
                            1 =>
                            [
                                'key' => 'key',
                                'type' => 'unchanged',
                                'value' => 'value',
                            ],
                            2 =>
                            [
                                'key' => 'ops',
                                'type' => 'added',
                                'value' => 'vops',
                            ],
                            ],
                        ],
                        ],
                    ],
                    1 =>
                    [
                        'key' => 'group1',
                        'type' => 'nested',
                        'children' =>
                        [
                        0 =>
                        [
                            'key' => 'baz',
                            'type' => 'updated',
                            'old' => 'bas',
                            'new' => 'bars',
                        ],
                        1 =>
                        [
                            'key' => 'foo',
                            'type' => 'unchanged',
                            'value' => 'bar',
                        ],
                        2 =>
                        [
                            'key' => 'nest',
                            'type' => 'updated',
                            'old' =>
                            [
                            'key' => 'value',
                            ],
                            'new' => 'str',
                        ],
                        ],
                    ],
                    2 =>
                    [
                        'key' => 'group2',
                        'type' => 'removed',
                        'value' =>
                        [
                        'abc' => 12345,
                        'deep' =>
                        [
                            'id' => 45,
                        ],
                        ],
                    ],
                    3 =>
                    [
                        'key' => 'group3',
                        'type' => 'added',
                        'value' =>
                        [
                        'deep' =>
                        [
                            'id' =>
                            [
                            'number' => 45,
                            ],
                        ],
                        'fee' => 100500,
                        ],
                    ],
                ],
                'plain'
            ]
        ];
    }

    public static function formJsonProvider(): iterable
    {
        return [
            [
                file_get_contents(__DIR__ . '/fixtures/expected-json-test.json'),
                [
                    0 =>
                    [
                        'key' => 'common',
                        'type' => 'nested',
                        'children' =>
                        [
                        0 =>
                        [
                            'key' => 'follow',
                            'type' => 'added',
                            'value' => false,
                        ],
                        1 =>
                        [
                            'key' => 'setting1',
                            'type' => 'unchanged',
                            'value' => 'Value 1',
                        ],
                        2 =>
                        [
                            'key' => 'setting2',
                            'type' => 'removed',
                            'value' => 200,
                        ],
                        3 =>
                        [
                            'key' => 'setting3',
                            'type' => 'updated',
                            'old' => true,
                            'new' => null,
                        ],
                        4 =>
                        [
                            'key' => 'setting4',
                            'type' => 'added',
                            'value' => 'blah blah',
                        ],
                        5 =>
                        [
                            'key' => 'setting5',
                            'type' => 'added',
                            'value' =>
                            [
                            'key5' => 'value5',
                            ],
                        ],
                        6 =>
                        [
                            'key' => 'setting6',
                            'type' => 'nested',
                            'children' =>
                            [
                            0 =>
                            [
                                'key' => 'doge',
                                'type' => 'nested',
                                'children' =>
                                [
                                0 =>
                                [
                                    'key' => 'wow',
                                    'type' => 'updated',
                                    'old' => '',
                                    'new' => 'so much',
                                ],
                                ],
                            ],
                            1 =>
                            [
                                'key' => 'key',
                                'type' => 'unchanged',
                                'value' => 'value',
                            ],
                            2 =>
                            [
                                'key' => 'ops',
                                'type' => 'added',
                                'value' => 'vops',
                            ],
                            ],
                        ],
                        ],
                    ],
                    1 =>
                    [
                        'key' => 'group1',
                        'type' => 'nested',
                        'children' =>
                        [
                        0 =>
                        [
                            'key' => 'baz',
                            'type' => 'updated',
                            'old' => 'bas',
                            'new' => 'bars',
                        ],
                        1 =>
                        [
                            'key' => 'foo',
                            'type' => 'unchanged',
                            'value' => 'bar',
                        ],
                        2 =>
                        [
                            'key' => 'nest',
                            'type' => 'updated',
                            'old' =>
                            [
                            'key' => 'value',
                            ],
                            'new' => 'str',
                        ],
                        ],
                    ],
                    2 =>
                    [
                        'key' => 'group2',
                        'type' => 'removed',
                        'value' =>
                        [
                        'abc' => 12345,
                        'deep' =>
                        [
                            'id' => 45,
                        ],
                        ],
                    ],
                    3 =>
                    [
                        'key' => 'group3',
                        'type' => 'added',
                        'value' =>
                        [
                        'deep' =>
                        [
                            'id' =>
                            [
                            'number' => 45,
                            ],
                        ],
                        'fee' => 100500,
                        ],
                    ],
                ],
                'json'
            ]
        ];
    }
}
