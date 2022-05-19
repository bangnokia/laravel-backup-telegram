<?php

namespace Bangnokia\LaravelBackupTelegram\Tests;

use Bangnokia\LaravelBackupTelegram\BackupToTelegram;
use Illuminate\Support\Facades\Event;
use Spatie\Backup\Events\BackupWasSuccessful;

class BackupToTelegramTest extends TestCase
{
    /** @test */
    public function it_should_be_fired_when_backup_successful()
    {
        Event::fake();

        Event::assertListening(BackupWasSuccessful::class, BackupToTelegram::class);
    }
}
