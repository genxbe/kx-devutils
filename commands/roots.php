<?php

namespace X\Commands\Commands;

use Kirby\CLI\CLI;
use X\Commands\Commands\Template;

return [
	'description' => 'Show all kirby root folders',
	'command' => static function (CLI $cli): void {
		new Roots($cli);
	},
];
