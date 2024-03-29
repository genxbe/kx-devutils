<?php

namespace X\Devutils\Commands\KirbyCommands;

use Kirby\CLI\CLI;
use Kirby\Toolkit\V;
use Kirby\Filesystem\F;
use X\Devutils\Commands\Command;

use function Laravel\Prompts\text;
use function Laravel\Prompts\alert;
use function Laravel\Prompts\error;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\warning;

class Down extends Command
{
	public static string $commandName = 'x:down';
	public static string $commandDescription = 'Sets a generic maintenance mode message with an optional email address.';
	public static array $commandArgs = [
		'email' => [
			'description' => 'The email address for the maintenance mode (optional)',
			'required' => false
		],
	];

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
			error('❌ Maintenance mode not enabled.');
			die();
		}

		$email = $cli->arg('email');

		if(empty($email))
		{
			do {
				$email = text(
					label: 'Enter the email address for the maintenance mode message (optional)',
					placeholder: 'E.g. support@getkirby.com',
					hint: 'This email address will be displayed on the maintenance mode message. Leave empty for none.',
				);
			} while(!V::email($email) && !empty($email));

			if(empty($email)) {
				F::write($this->kirby->root().'/.maintenance', '');
			}
		}

		if(!empty($email))
		{
			F::write($this->kirby->root().'/.maintenance', $email);
		}

		warning('🚧 Maintenance mode enabled. Your site can only be accessed by logged in users.');
		die();
	}
}
