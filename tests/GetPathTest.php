<?php

namespace Tests\Differ\Parsers;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

use function Differ\Parsers\{isAbsolute, getPath};

#[CoversFunction('Differ\Parsers\isAbsolute')]
#[CoversFunction('Differ\Parsers\getPath')]
class GetPathTest extends TestCase
{
    public function testIsAbsolute(): void
    {
        $this->assertTrue(isAbsolute('/'));
        $this->assertTrue(isAbsolute('/etc/files'));
        $this->assertFalse(isAbsolute('~/path'));
        $this->assertFalse(isAbsolute('bin/bin'));
    }

    public function testGetPath(): void
    {
        $this->assertFileIsReadable(getPath('tests/fixtures/file1.json'));
        $this->assertFileIsReadable(getPath(__DIR__ . '/fixtures/file1.json'));
        $this->assertFileDoesNotExist(getPath('fixtures/file1.json'));
    }
}
