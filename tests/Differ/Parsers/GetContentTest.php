<?php

namespace Tests\Differ\Parsers;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\Parsers\getContent;

#[CoversFunction('Differ\Differ\Parsers\getContent')]
class GetContentTest extends TestCase
{
    public function testGetContent(): void
    {
        $this->assertEquals(
            [
                'host' => 'hexlet.io',
                'timeout' => 50,
                'proxy' => '123.234.53.22',
                'follow' => false
            ],
            getContent('/home/felt/php-project-48/tests/fixtures/file1.json')
        );
        $this->expectException(\Exception::class);

        $content = getContent('/');
    }
}
