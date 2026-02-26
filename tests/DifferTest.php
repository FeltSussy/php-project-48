<?php

namespace Tests\Differ;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;

use function Differ\Differ\genDiff;
use function Differ\Parsers\getFileFormat;

#[CoversFunction('Differ\Differ\genDiff')]
class DifferTest extends TestCase
{
    #[DataProvider('differProvider')]
    public function testDiffer(string $path1, string $path2): void
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
          }
          
          EOT,
            genDiff($path1, $path2)
        );

        $this->assertEquals(
            <<<EOT
            {
              + follow: false
                host: hexlet.io
              + proxy: 123.234.53.22
              - timeout: 20
              + timeout: 50
              - verbose: true
            }
            
            EOT,
            genDiff($path2, $path1)
        );
    }

    public static function differProvider(): array
    {
        return [
        [__DIR__ . "/fixtures/file1.json", __DIR__ . "/fixtures/file2.json"],
        [__DIR__ . "/fixtures/file1.yaml", __DIR__ . "/fixtures/file2.yml"],
        ];
    }

    #[Group('debug')]
    public function testDebug(): void
    {
        $path1 = __DIR__ . "/fixtures/file2.yml";
        $path2 = __DIR__ . "/fixtures/file1.json";

        // print_r(genDiff($path1, $path2));

        echo getFileFormat($path1);
        $this->assertTrue(true);
    }
}
