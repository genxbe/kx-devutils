<?php

namespace X\Devutils\Commands\KirbyCommands;

use Kirby\CLI\CLI;
use X\Devutils\Commands\Command;

use function Laravel\Prompts\table;

class Roots extends Command
{
	public static string $commandName = 'x:roots';
	public static string $description = 'Pretty list of all configured roots';
	public static array $commandArgs = [];

	public function __construct(CLI $cli)
	{
		parent::__construct($cli);

		$roots = $cli->roots();
		ksort($roots);

        table(
            headers: ['Root', 'Path'],
            rows: array_map(
                static function ($root, $path) {
                    return [$root, $path];
                },
                array_keys($roots),
                array_values($roots)
            )
        );
	}
}
