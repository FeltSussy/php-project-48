<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class ParserTest extends TestCase
{
    public function testParse(): void
    {
        $path1 = __DIR__ . "/fixtures/file1.json";
        $path2 = __DIR__ . "/fixtures/file2.json";

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
            genDiff($path1, $path2)
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
            genDiff($path2, $path1)
        );
    }
}
