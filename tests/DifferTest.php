<?php

namespace Tests\Differ;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    #[DataProvider('differProvider')]
    public function testDiffer(string $formats): void
    {
        $path1 = __DIR__ . "/fixtures/file1.{$formats}";
        $path2 = __DIR__ . "/fixtures/file2.{$formats}";

        $expectedStylish = __DIR__ . '/fixtures/stylish-test.txt';
        $expectedPlain = __DIR__ . '/fixtures/plain-test.txt';
        $expectedJson = __DIR__ . '/fixtures/json-test.txt';

        $this->assertStringEqualsFile($expectedStylish, genDiff($path1, $path2));
        $this->assertStringEqualsFile($expectedStylish, genDiff($path1, $path2, 'stylish'));
        $this->assertStringEqualsFile($expectedPlain, genDiff($path1, $path2, 'plain'));
        $this->assertStringEqualsFile($expectedJson, genDiff($path1, $path2, 'json'));
    }

    public static function differProvider(): array
    {
        return [
            'Json' => ['json'],
            'Yaml' => ['yaml'],
        ];
    }
}
