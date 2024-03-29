<?php

namespace X\Commands\Commands;

use Kirby\CLI\CLI;

use function Laravel\Prompts\info;

class Template extends Command
{
	public function __construct(CLI $cli)
	{
		parent::__construct($cli);

		info('Hello world!');
	}
}
