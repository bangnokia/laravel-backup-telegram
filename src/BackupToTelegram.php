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

    public function handle(BackupWasSuccessful $event): void
    {
        $backup = $event->backupDestination->newestBackup();

        $threshold = 40; // in MB
        consoleOutput()->info("File size is {$backup->sizeInBytes()} bytes");
        if ($backup->sizeInBytes() > $threshold * 1024 * 1024) {
            consoleOutput()->info("File size is bigger than {$threshold}MB, chunking the file into multiple parts");
            $response = $this->splitAndUpload($backup, $threshold);
        } else {
            $response = $this->singleUpload($backup);
        }

        if ($response['ok']) {
            consoleOutput()->info('Uploaded to Telegram successful!');
        } else {
            consoleOutput()->error('Can not upload to Telegram');
        }
    }

    public function singleUpload(Backup $backup): array
    {
        $response = Http::attach('document', $backup->disk()->get($backup->path()), $backup->path())
            ->timeout(300)
            ->post(
                "https://api.telegram.org/bot{$this->token}/sendDocument",
                ['chat_id' => $this->chatId, 'caption' => config('app.name')]
            )->throw();

        return $response->json();
    }

    public function splitAndUpload(?Backup $backup, int $threshold): array
    {
        $sword = new Sword();

        $parts = $sword->slash($backup->disk()->path($backup->path()), $threshold);

        foreach ($parts as $part) {
            consoleOutput()->info("Uploading part {$part}");
            $response = Http::attach('document', file_get_contents($part), basename($part))
                ->timeout(300) // is this enough?
                ->post(
                    "https://api.telegram.org/bot{$this->token}/sendDocument",
                    ['chat_id' => $this->chatId, 'caption' => config('app.name')]
                )->throw();
        }

        // delete the parts
        $sword->cleanup($parts);

        return $response->json(); // return the last response
    }
}
