# Laravel backup Telegram

This package requires [spatie/laravel-backup](https://github.com/spatie/laravel-backup) to work, it supports for uploading the backup file to a telegram channel.

## Setup

Edit your `config/services.php` file, then add these lines. I tried to match config with the package [Telegram notification channel](https://github.com/laravel-notification-channels/telegram)
```
'telegram-bot-api' => [
    'token' => env('TELEGRAM_BOT_TOKEN'),
    'chat_id' => env('TELEGRAM_CHAT_ID')
]
```
