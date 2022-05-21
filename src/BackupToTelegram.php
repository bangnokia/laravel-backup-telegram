<?php

namespace Bangnokia\LaravelBackupTelegram;

use Illuminate\Support\Facades\Http;
use Spatie\Backup\Events\BackupWasSuccessful;

class BackupToTelegram
{
    protected string $token;

    protected string $chatId;

    public function __construct()
    {
        $this->token = config('services.telegram-bot-api.token');
        $this->chatId = config('services.telegram-bot-api.chat_id');
    }

    public function handle(BackupWasSuccessful $event)
    {
        $backup = $event->backupDestination->newestBackup();

        $response = Http::attach('document', $backup->disk()->get($backup->path()), $backup->path())
            ->post(
                "https://api.telegram.org/bot{$this->token}/sendDocument",
                ['chat_id' => $this->chatId, 'caption' => config('app.name')]
            );


        return $response['ok']
            ? consoleOutput()->info('Uploaded to Telegram successful!')
            : consoleOutput()->error('Can not upload to Telegram');
    }
}
