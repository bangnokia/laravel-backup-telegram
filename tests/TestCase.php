<?php

namespace Bangnokia\LaravelBackupTelegram\Tests;

use Bangnokia\LaravelBackupTelegram\ServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }
}
