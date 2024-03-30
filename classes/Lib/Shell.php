<?php

namespace X\Devutils\Lib;

use Kirby\Toolkit\Str;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Shell
{
	public static function run(string $cmd): array
    {
		$workingDirectory = getcwd();
		$command = Str::split($cmd, ' ');

        $process = new Process($command, $workingDirectory);
        $process->setTimeout(3600);

        try {
            $process->mustRun();
        } catch (ProcessFailedException $exception) {
			$error = match(true) {
				Str::contains($exception->getMessage(), 'No such file or directory') => "❌ The command {$cmd} was not found.",
				Str::contains($exception->getMessage(), 'requirements could not be resolved') => "❌ There was a problem with PHP version compatability.",
				Str::contains($exception->getMessage(), 'Could not find a matching') => "❌ The github repo was not found.",
				default => "❌ There was a problem running the command.",
			};

			return [
				'success' => false,
				'msg' => $error,
			];
        }

		return [
			'success' => true,
			'msg' => '',
		];
    }
}
