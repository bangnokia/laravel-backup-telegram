<?php

namespace Bangnokia\LaravelBackupTelegram\Tests;

use Bangnokia\LaravelBackupTelegram\Sword;

class SwordTest extends TestCase
{
    /** @test */
    public function it_could_split_file_into_chunks()
    {
        $sword = new Sword();
        $testFile = __DIR__ . '/dummy/test.zip';

        $parts = $sword->slash($testFile, 1);

        $this->assertIsArray($parts);
        $this->assertCount(2, $parts);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $parts = glob(__DIR__ . '/dummy/test.zip.part*');

        foreach ($parts as $part) {
            unlink($part);
        }
    }
}
