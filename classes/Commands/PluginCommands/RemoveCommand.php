<?php

namespace X\Devutils\Commands\PluginCommands;

use Kirby\CLI\CLI;
use Kirby\Cms\Plugin;
use X\Devutils\Lib\Shell;

use X\Devutils\Lib\Plugins;
use X\Devutils\Commands\Command;

use function Laravel\Prompts\info;
use function Laravel\Prompts\error;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\progress;

class RemoveCommand extends Command
{
	public static string $commandName = 'x:plugins:remove';
	public static string $commandDescription = 'Remove a specific plugin via composer';
	public static array $commandArgs = [];

	public function __construct(CLI $cli)
	{
		parent::__construct($cli);

		$plugins = new Plugins($this->kirby);
		$plugin = $plugins->selectPlugin();

		$confirmed = confirm(
			label: "Are you sure you want to remove {$plugin}?",
			default: false,
		);

		if(!$confirmed)
		{
			return error('❌ Plugin not removed.');
		}

		$plugin = $this->kirby->plugin($plugin);

		$this->deletePlugin($plugin);
	}

	private function deletePlugin(Plugin $plugin): void
	{
		$composerRunPrefix = option('genxbe.devutils.install.composerRunPrefix');
		$pluginName = $plugin->name();
		$pluginPackage = $plugin->info()['name'] ?? '';

		if(empty($pluginPackage)) {
			error("❌ The plugin {$pluginName} does not have a valid composer configuration.");
			return;
		}

		$success = progress(
			label: "Deleting {$pluginName}...",
			steps: 1,
			callback: function() use($plugin, $composerRunPrefix, $pluginName, $pluginPackage) {
				Shell::run("{$composerRunPrefix} composer remove {$pluginPackage}");
			},
		);

		if($success[0] === false) {
			error("❌ The deletion of {$pluginName} failed.");
			return;
		}

		info("🚀 {$pluginName} deleted via composer.");
	}
}
