<?php

namespace X\Commands\Commands;

use Kirby\CLI\CLI;
use Kirby\Filesystem\F;
use X\Commands\Lib\Shell;

use function Laravel\Prompts\info;
use function Laravel\Prompts\alert;
use function Laravel\Prompts\error;
use function Laravel\Prompts\warning;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\progress;

class Install extends Command
{
	private array $options;

	public function __construct(CLI $cli)
	{
		parent::__construct($cli);

		$this->options = option('genxbe.kx-devutils.x-install');

		if($this->options['createEnv']) {
			$this->checkEnv();
		}

		if(empty($cli->arg('--nophp'))) {
			$this->composerInstall();
		}

		if(empty($cli->arg('--nojs'))) {
			$this->jsInstall();
		}
	}

	private function jsInstall(): void
	{
		if(F::exists($this->kirby->root().'/yarn.lock')) {
			$framework = 'yarn';
		}

		if(F::exists($this->kirby->root().'/package.json')) {
			$framework = 'npm';
		}

		if(empty($framework)) {
			error('❌ JS Framework not installed, package.json and yarn.lock not found.');
			return;
		}

		$success = progress(
			label: "Running {$framework} install...",
			steps: 1,
			callback: function() use($framework) {
				return Shell::run("{$framework} install");
			},
		);

		if($success[0] === false) {
			error('❌ npm install failed.');
			return;
		}

		info("🚀 {$framework} installed");
	}

	private function composerInstall(): void
	{
		if(!F::exists($this->kirby->root().'/composer.json')) {
			error('❌ Composer not installed, composer.json not found.');
			return;
		}

		if(!empty($this->options['composerRunPrefix'])) {
			$composerRunPrefix = ' '.$this->options['composerRunPrefix'];
		} else {
			$composerRunPrefix = '';
		}

		$success = progress(
			label: 'Running composer install...',
			steps: 1,
			callback: function() use($composerRunPrefix) {
				Shell::run("{$composerRunPrefix} composer install");
			},
		);

		if($success[0] === false) {
			error('❌ composer install failed.');
			return;
		}

		info('🚀 Composer installed');
	}

	private function checkEnv(): void
	{
		$env = $this->kirby->root().'/.env';
		$envExample = $this->kirby->root('root').'/.env.example';

		if(!F::exists($env) && F::exists($envExample)) {
			error('❌ .env file not found.');
			info('🚀 Copying .env.example to .env...');

			F::copy($envExample, $env);
		}

		if(!F::exists($env) && !F::exists($envExample)) {
			alert('❌ .env & .env.example file not found.');
			info('🚀 Creating new basic .env file...');

			$envContent = <<<'ENV'
			# APP_ENV = local|staging|production
			APP_ENV=local
			APP_DEBUG=false
			ENV;

			F::write($env, $envContent);
		}

		info('🚀 .env file is correctly installed.');
	}

}
