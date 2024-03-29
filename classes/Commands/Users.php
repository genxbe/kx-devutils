<?php

namespace X\Devutils\Commands;

use Kirby\CLI\CLI;

use function Laravel\Prompts\table;
use function Laravel\Prompts\error;

class Users extends Command
{
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
