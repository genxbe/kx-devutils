<?php

namespace X\Devutils\Lib;

use Kirby\Toolkit\Str;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

use function Laravel\Prompts\error;

class Shell
{
	public static function run(string $cmd): bool
    {
		$workingDirectory = getcwd();
		$command = Str::split($cmd, ' ');

        $process = new Process($command, $workingDirectory);
        $process->setTimeout(3600);

        try {
            $process->mustRun();
        } catch (ProcessFailedException $exception) {
			ray($exception);
			return false;
        }

		return true;
    }
}
