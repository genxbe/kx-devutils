<?php

namespace X\Commands\Commands;

use Kirby\CLI\CLI;
use X\Commands\Commands\Users;

return [
	'description' => 'Show all users',
	'command' => static function (CLI $cli): void {
		new Users($cli);
	},
];
