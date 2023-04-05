<?php

namespace Bangnokia\LaravelBackupTelegram;

use Spatie\Backup\BackupDestination\Backup;
use Symfony\Component\Process\Process;

class Sword
{
    public function slash(string $fullPath, int $megaBytes = 49): array
    {
        $process = Process::fromShellCommandline("split -b {$megaBytes}M {$fullPath} {$fullPath}.part");
        $result = $process->run();

        if ($result !== 0) {
            throw new \Exception('Can not split the file');
        }

        // get the list of parts
        $parts = glob("{$fullPath}.part*");

        return $parts;
    }

    public function cleanup(array $parts): void
    {
        foreach ($parts as $part) {
            unlink($part);
        }
    }
}
