<?php

namespace X\Devutils\Commands\KirbyCommands;

use Kirby\CLI\CLI;
use X\Devutils\Commands\Command;

use function Laravel\Prompts\table;
use function Laravel\Prompts\error;

class UsersCommand extends Command
{
	public static string $commandName = 'x:users';
	public static string $description = 'Show all users of the kirby site';
	public static array $commandArgs = [];

	public function __construct(CLI $cli)
	{
		parent::__construct($cli);

		$users = $this->kirby->users();

		foreach($users as $user) {
			$arr['id'] = $user->id();
			$arr['email'] = $user->email();
			$arr['role'] = $user->role()->name();
			$arr['language'] = $user->language();
			$arr['username'] = $user->username();

			$table[] = $arr;
		}

		if(empty($table)) {
			return error('❌ No users found, you can use `kirby make:user` to create one.');
		}

        table(
            headers: ['ID', 'E-mail', 'Role', 'Language', 'Username'],
            rows: $table,
        );
	}
}
