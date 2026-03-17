<?php

namespace Tests\Differ\Parsers;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

use function Differ\Parsers\getContent;

#[CoversFunction('Differ\Parsers\getContent')]
class GetContentTest extends TestCase
{
    public function testGetContent(): void
    {
        $this->assertEquals(
            [
                'common' => [
                    'setting1' => 'Value 1',
                    'setting2' => 200,
                    'setting3' => true,
                    'setting6' => [
                        'doge' => [
                            'wow' => ''
                        ],
                        'key' => 'value'
                    ]
                ],
                'group1' => [
                    'baz' => 'bas',
                    'foo' => 'bar',
                    'nest' => [
                        'key' => 'value'
                    ]
                ],
                'group2' => [
                    'abc' => 12345,
                    'deep' => [
                        'id' => 45
                    ]
                ]
            ],
            getContent(__DIR__ . '/fixtures/file1.json')
        );
        $this->expectException(\InvalidArgumentException::class);

        $content = getContent('/');
    }
}
