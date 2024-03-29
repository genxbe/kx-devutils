<?php

namespace X\Commands\Commands;

use Kirby\CLI\CLI;

use function Laravel\Prompts\table;

class Roots extends Command
{
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
