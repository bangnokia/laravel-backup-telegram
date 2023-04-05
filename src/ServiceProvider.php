<?php

namespace Bangnokia\LaravelBackupTelegram;

use Illuminate\Support\Facades\Event;
use Spatie\Backup\Events\BackupWasSuccessful;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Event::listen(BackupWasSuccessful::class, Transporter::class);
    }
}
