<?php

namespace X\Commands\Lib;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

use function Laravel\Prompts\error;

class Shell
{
	public static function run(string $cmd): bool
    {
		$command = explode(' ', $cmd);

        $workingDirectory = getcwd();

        $process = new Process($command, $workingDirectory);
        $process->setTimeout(3600);

        try {
            $process->mustRun();
        } catch (ProcessFailedException $exception) {
			return false;
        }

		return true;
    }
}
