<?php

namespace Bangnokia\LaravelBackupTelegram;

use Symfony\Component\Process\Process;

class Sword
{
    public function slash(string $filePath, int $megaBytes = 49): array
    {
        $process = Process::fromShellCommandline("split -b {$megaBytes}M {$filePath} {$filePath}.part");
        $result = $process->run();

        if ($result !== 0) {
            throw new \RuntimeException('Can not split the file');
        }

        // get the list of parts
        $parts = glob("{$filePath}.part*");

        return $parts;
    }

    public function cleanup(array $parts): void
    {
        foreach ($parts as $part) {
            unlink($part);
        }
    }
}
