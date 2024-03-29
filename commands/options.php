<?php

namespace X\Commands\Commands;

use Kirby\CLI\CLI;
use X\Commands\Commands\Options;

return [
	'description' => 'Show all options or select a plugin to show its options',
	'args' => [
		'--all' => [
			'longPrefix'  => 'all',
			'description' => 'Show all kirby and plugin options',
			'noValue'     => true,
		],
	],
	'command' => static function (CLI $cli): void {
		new Options($cli);
	},
];
