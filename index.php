<?php

use X\Devutils\Commands;

$options = [
	'maintenance' => true,
    'install' => [
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
		Commands\KirbyCommands\UpCommand::render(),
		Commands\KirbyCommands\DownCommand::render(),
		Commands\KirbyCommands\RootsCommand::render(),
		Commands\KirbyCommands\UsersCommand::render(),
		Commands\KirbyCommands\RoutesCommand::render(),
		Commands\KirbyCommands\OptionsCommand::render(),
		Commands\KirbyCommands\InstallCommand::render(),

		/** Plugin Commands **/
		Commands\PluginCommands\ListCommand::render(),
		Commands\PluginCommands\InstallCommand::render(),
		Commands\PluginCommands\RemoveCommand::render(),
	),
	'hooks' => [
        'route:after' => fn() => X\Devutils\Lib\Toolkit::checkForMaintenance(),
    ],
]);
