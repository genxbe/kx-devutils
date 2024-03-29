<?php

namespace X\Devutils\Commands\KirbyCommands;

use Kirby\CLI\CLI;
use Kirby\Filesystem\F;
use X\Devutils\Commands\Command;

use function Laravel\Prompts\info;
use function Laravel\Prompts\error;
use function Laravel\Prompts\confirm;

class Up extends Command
{
	public static string $commandName = 'x:up';
	public static string $commandDescription = 'Removes the generic maintenance mode message.';
	public static array $commandArgs = [];

	public function __construct(CLI $cli)
	{
		parent::__construct($cli);

		$confirmed = confirm(
			label: 'Are you sure you want to enable maintenance mode?',
			default: false,
			hint: 'This will prevent non-logged in users from accessing the site.',
		);

		if(!$confirmed)
		{
			error('❌ Maintenance mode not disabled.');
			die();
		}

		F::unlink($this->kirby->root().'/.maintenance');

		info('✅ Maintenance mode disabled.');
		die();
	}
}
