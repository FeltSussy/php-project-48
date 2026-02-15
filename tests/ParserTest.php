<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class ParserTest extends TestCase
{
    public function testParse(): void
    {
        $this->assertEquals(
            <<<EOT
            {
              - follow: false
                host: hexlet.io
              - proxy: 123.234.53.22
              - timeout: 50
              + timeout: 20
              + verbose: true
            }\n
            EOT,
            genDiff("/home/felt/php-project-48/files/file1.json", "/home/felt/php-project-48/files/file2.json")
        );

        $this->assertEquals(
            <<<EOT
            {
                host: hexlet.io
              - timeout: 20
              + timeout: 50
              - verbose: true
              + follow: false
              + proxy: 123.234.53.22
            }\n
            EOT,
            genDiff("/home/felt/php-project-48/files/file2.json", "/home/felt/php-project-48/files/file1.json")
        );
    }
}
