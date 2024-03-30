<?php

use X\Devutils\Commands;
use X\Devutils\Lib\Toolkit;

$options = [
	'maintenance' => true,
    'install' => [
		'createEnv' => false,
		'composerPrefix' => '',
	],
	'plugins' => [
		'packages' => [
			// 'seo' => [
			// 	'bnomei/kirby3-feed',
			// 	'tobimori/kirby-seo',
			// ],
			// 'favourites' => [
			// 	'genxbe/kirby3-ray',
			// 	'bnomei/autoloader-for-kirby',
			// 	'bnomei/kirby3-feed',
			// ],
		]
	],
	'availableCommands' => [
		/** Kirby Commands **/
		Commands\KirbyCommands\UpCommand::class,
		Commands\KirbyCommands\DownCommand::class,
		Commands\KirbyCommands\RootsCommand::class,
		Commands\KirbyCommands\UsersCommand::class,
		Commands\KirbyCommands\RoutesCommand::class,
		Commands\KirbyCommands\OptionsCommand::class,
		Commands\KirbyCommands\InstallCommand::class,

		/** Plugin Commands **/
		Commands\PluginCommands\ListCommand::class,
		Commands\PluginCommands\RemoveCommand::class,
		Commands\PluginCommands\InstallCommand::class,
	],
	'disabledCommands' => [
		//
	],
];

if(option('debug') && \Kirby\Filesystem\F::exists(kirby()->root().'/vendor/spatie/ray/composer.json')) {
	ray()->enable();
}

Kirby::plugin('genxbe/kx-devutils', [
	'options' => $options,
	'snippets' => [
		'x/maintenance' => __DIR__ . '/snippets/maintenance.php',
	],
	'hooks' => [
        'route:after' => fn() => X\Devutils\Lib\Toolkit::checkForMaintenance(),
		'system.loadPlugins:after' => fn() => Toolkit::renderCommands(),
    ],
]);
