<?php

use Kirby\Cms\App;

return [
    'description' => 'Run scheduled tasks',
    'command' => static function ($cli): void {
		$kirby = App::instance();
        dd('Running schedule tasks...');
    }
];
