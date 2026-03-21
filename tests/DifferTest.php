<?php

namespace Tests\Differ;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    #[DataProvider('differProvider')]
    public function testDiffer(string $format): void
    {
        $path1 = self::getFixtureFullPath("file1.{$format}");
        $path2 = self::getFixtureFullPath("file2.{$format}");

        $expectedStylish = self::getFixtureFullPath('stylish-test.txt');
        $expectedPlain = self::getFixtureFullPath('plain-test.txt');
        $expectedJson = self::getFixtureFullPath('json-test.txt');

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

    private static function getFixtureFullPath(string $fileName): string
    {
        return __DIR__ . "/fixtures/{$fileName}";
    }
}
