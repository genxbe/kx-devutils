<?php

namespace X\Devutils\Commands\KirbyCommands;

use Kirby\CLI\CLI;
use X\Devutils\Commands\Command;

use function Laravel\Prompts\info;

class Template extends Command
{
	private static string $commandName = 'x:command';
	private static string $commandDescription = 'This is a command';
	private static array $commandArgs = [
		'--all' => [
			'description' => 'Used to copy/paste to create a new command',
		],
	];

	public function __construct(CLI $cli)
	{
		parent::__construct($cli);

		info('Hello world!');
	}
}
