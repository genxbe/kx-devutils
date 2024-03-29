<?php

namespace X\Commands\Commands;

use Kirby\CLI\CLI;
use X\Commands\Commands\Template;

return [
	'description' => 'Template command',
	'args' => [
		'--all' => [
			'description' => 'Used to copy/paste to create a new command',
		],
	],
	'command' => static function (CLI $cli): void {
		new Template($cli);
	},
];
