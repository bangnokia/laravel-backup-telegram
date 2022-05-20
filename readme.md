# Laravel backup Telegram

This package requires [spatie/laravel-backup](https://github.com/spatie/laravel-backup) to work, it supports for uploading the backup file to a telegram channel.

Because this package use [Telegram API](https://core.telegram.org/bots/api#senddocument), the file size be limited to 50MB. So if your database is big, this solution isn't for you.

## Setup

Install this package 
```
composer require bangnokia/laravel-backup-telegram
```

Edit your `config/services.php` file, then add these lines. I tried to match config with the package [Telegram notification channel](https://github.com/laravel-notification-channels/telegram)
```
'telegram-bot-api' => [
    'token' => env('TELEGRAM_BOT_TOKEN'),
    'chat_id' => env('TELEGRAM_CHAT_ID')
]
```
