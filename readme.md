# Laravel backup Telegram

This package requires [spatie/laravel-backup](https://github.com/spatie/laravel-backup) to work, it supports for uploading the backup file to a telegram channel.

Because this package use [Telegram API](https://core.telegram.org/bots/api#senddocument), the file size must be limited to 50MB. So if your database is big, this solution isn't for your bussiness.

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

## Usage

This package automatically add a event listener when a backup created successfully. So you just simply run `php artisan backup:run`, your backup file will be uploaded to Telegram channel.

If you don't use the email notitication, please backup the `backup.php` config file and change `mail.to` to an empty string. Refer to this issue [#1](https://github.com/bangnokia/laravel-backup-telegram/issues/1)
