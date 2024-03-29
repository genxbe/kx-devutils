<?php

namespace X\Commands\Commands;

use Kirby\CLI\CLI;
use X\Commands\Commands\Install;

return [
	'description' => 'Install the project, do some default stuff',
	'args' => [
		'--nojs' => [
			'longPrefix'  => 'nojs',
			'description' => 'Don\'t run npm/yarn',
			'noValue'     => true,
		],
		'--nophp' => [
			'longPrefix'  => 'nophp',
			'description' => 'Don\'t run composer',
			'noValue'     => true,
		],
	],
	'command' => static function (CLI $cli): void {
		new Install($cli);
	},
];
