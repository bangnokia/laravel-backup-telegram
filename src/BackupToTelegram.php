<?php

namespace Bangnokia\LaravelBackupTelegram;

use Illuminate\Support\Facades\Http;
use Spatie\Backup\BackupDestination\Backup;
use Spatie\Backup\Events\BackupWasSuccessful;

class BackupToTelegram
{
    protected string $token;

    protected string $chatId;

    public function __construct(?string $token = null, ?string $chatId = null)
    {
        $this->token = $token ?? config('services.telegram-bot-api.token');
        $this->chatId = $chatId ?? config('services.telegram-bot-api.chat_id');
    }

    public function handle(BackupWasSuccessful $event)
    {
        $backup = $event->backupDestination->newestBackup();

        // if file size bigger than 50MB, chunk the files into multiple parts
        if ($backup->sizeInBytes() > 50 * 1024 * 1024) {
            $response = $this->splitAndUpload($backup, 50);
        } else {
            $response = $this->singleUpload($backup);
        }

        return $response['ok']
            ? consoleOutput()->info('Uploaded to Telegram successful!')
            : consoleOutput()->error('Can not upload to Telegram');
    }

    public function singleUpload(Backup $backup): array
    {
        $response = Http::attach('document', $backup->disk()->get($backup->path()), $backup->path())
            ->post(
                "https://api.telegram.org/bot{$this->token}/sendDocument",
                ['chat_id' => $this->chatId, 'caption' => config('app.name')]
            )->throw();

        return $response->json();
    }

    public function splitAndUpload(?Backup $backup): array
    {
        $sword = new Sword();
        // chunk the file into 50MB parts
        $parts = $sword->slash($backup->disk()->path($backup->path()));

        foreach ($parts as $part) {
            $response = Http::attach('document', $part, basename($part))
                ->post(
                    "https://api.telegram.org/bot{$this->token}/sendDocument",
                    ['chat_id' => $this->chatId, 'caption' => config('app.name')]
                )->throw();
        }

        // delete the parts
        foreach ($parts as $part) {
            unlink($part);
        }

        return $response->json(); // return the last response
    }
}
