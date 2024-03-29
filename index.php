<?php

use X\Devutils\Commands;

$options = [
	'maintenance' => true,
    'x-install' => [
		'createEnv' => false,
		'composerPrefix' => '',
	],
];

ray()->enable();

Kirby::plugin('genxbe/kx-devutils', [
	'options' => $options,
	'snippets' => [
		'x/maintenance' => __DIR__ . '/snippets/maintenance.php',
	],
	'commands' => A::merge(
		/** Kirby Commands **/
		Commands\KirbyCommands\Up::render(),
		Commands\KirbyCommands\Down::render(),
		Commands\KirbyCommands\Roots::render(),
		Commands\KirbyCommands\Users::render(),
		Commands\KirbyCommands\Routes::render(),
		Commands\KirbyCommands\Options::render(),
		Commands\KirbyCommands\Install::render(),
	),
	'hooks' => [
        'route:after' => fn() => X\Devutils\Lib\Toolkit::checkForMaintenance(),
    ],
]);
