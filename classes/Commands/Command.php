<?php

namespace X\Devutils;

use Kirby\Cms\App;
use Kirby\CLI\CLI;

private static string $commandName = 'x:command';
private static string $commandDescription = 'This is a command';
private static array $commandArgs = [];

class Command
{
	protected CLI $cli;
	protected App $kirby;

	public function __construct(CLI $cli)
	{
		$this->cli = $cli;
		$this->kirby = App::instance();
	}

	public static function render()
	{
		$class = static::class;

		return [
			static::$commandName => [
				'description' => static::$commandDescription,
				'args' => static::$commandArgs,
				'command' => fn($cli) => new $class($cli),
			],
		];
	}
}
