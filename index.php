<?php

$options = [
    'x-install' => [
		'createEnv' => false,
		'useNvm' => false,
		'composerPrefix' => '',
	]
];

ray()->enable();

Kirby::plugin('genxbe/kx-devutils', [
	'options' => $options,
	'commands' => [
		'x:install' => require __DIR__.'/commands/install.php',
		'x:options' => require __DIR__.'/commands/options.php',
		'x:routes' => require __DIR__.'/commands/routes.php',
		'x:roots' => require __DIR__.'/commands/roots.php',
		// 'x:users' => require __DIR__.'/commands/users.php',
	]
]);
