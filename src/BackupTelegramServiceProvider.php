<?php

namespace Bangnokia\LaravelBackupTelegram;

use Illuminate\Support\ServiceProvider;
use Spatie\Backup\Events\BackupWasSuccessful;
use Spatie\Backup\Events\BackupHasFailed;
use Bangnokia\LaravelBackupTelegram\Listeners\BackupSuccessfulListener;
use Bangnokia\LaravelBackupTelegram\Listeners\BackupFailedListener;
use Illuminate\Support\Facades\Event;

class BackupTelegramServiceProvider extends ServiceProvider
{
    // ...existing code...

    public function boot()
    {
        // Publish config file - adjust for Laravel 11+ compatibility
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/backup-telegram.php' => config_path('backup-telegram.php'),
            ], 'config');
        }

        // Register event listeners
        Event::listen(BackupWasSuccessful::class, BackupSuccessfulListener::class);
        Event::listen(BackupHasFailed::class, BackupFailedListener::class);
    }

    // ...existing code...
}
