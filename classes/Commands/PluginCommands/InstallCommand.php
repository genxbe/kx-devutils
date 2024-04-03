<?php

namespace X\Devutils\Commands\PluginCommands;

use X\Devutils\Lib\Shell;
use X\Devutils\Lib\Plugins;
use X\Devutils\Commands\Command;

use Kirby\CLI\CLI;
use Kirby\Data\Json;
use Kirby\Http\Remote;
use Kirby\Toolkit\Str;

use function Laravel\Prompts\info;
use function Laravel\Prompts\error;
use function Laravel\Prompts\warning;
use function Laravel\Prompts\search;
use function Laravel\Prompts\suggest;
use function Laravel\Prompts\progress;

class InstallCommand extends Command
{
	public static string $commandName = 'x:plugins:install';
	public static string $commandDescription = 'Install a plugin via composer.';
	public static array $commandArgs = [
		'package' => [
			'description' => 'The pre-configured plugin package you would like to install',
			'required' => false
		],
	];

	private string|null $composerRunPrefix;

	public function __construct(CLI $cli)
	{
		parent::__construct($cli);
		$this->composerRunPrefix = option('genxbe.devutils.install.composerRunPrefix');

		$package = $cli->arg('package');

		if(empty($package)) {
			return $this->installPlugin();
		}

		$this->installPackage($package);
	}

	private function installPackage(string $package)
	{
		$plugins = option("genxbe.kx-devutils.plugins.packages.{$package}");

		if(empty($plugins)) {
			error("❌ The package was not found.");
			return;
		}

		$progress = progress(label: 'Installing plugins', steps: count($plugins));

		foreach($plugins as $plugin) {
			Shell::run("{$this->composerRunPrefix} composer require {$plugin}");
			$progress->label('Installing '.$plugin);
			$progress->advance();
		}

		$progress->finish();
	}

	private function installPlugin()
	{
		$pluginsUrl = 'https://getkirby.com/plugins.json';
        $plugins = Remote::get($pluginsUrl);
		$plugins = Json::decode($plugins->content());

		$plugins = $this->selectCategory($plugins);

		if(empty($plugins)) {
			error("❌ The category was not found.");
			return;
		}

		$plugin = $this->selectPlugin($plugins);

		if(empty($plugin)) {
			error("❌ The plugin was not found.");
			return;
		}

		$success = progress(
			label: "Installing {$plugin['name']} by {$plugin['author']}...",
			steps: 1,
			callback: function() use($plugin) {
				return Shell::run("{$this->composerRunPrefix} composer require {$plugin['package']}");
			},
		);

		if($success[0]['success'] === false) {
			error("{$success[0]['msg']}. You can always install the plugin manually via {$plugin['url']}");
			return;
		}

		info("🚀 {$plugin['name']} is installed via composer.");
	}

	private function selectCategory($plugins)
	{
		$categories = array_column($plugins, 'category');
		$categories = array_unique($categories);
		$categories = array_filter($categories);

		$selectedCategory = suggest(
			label: 'Select the plugin category (Leavy empty for all)',
			options: $categories,
			placeholder: 'E.g. Panel, SEO, ...',
			hint: 'Start typing or use the down arrow to select from a list',
		);

		if(empty($selectedCategory))
		{
			return $plugins;
		}

		$filteredPlugins = array_filter($plugins, function($plugin) use($selectedCategory) {
			return $plugin['category'] === $selectedCategory;
		});

		return $filteredPlugins;
	}

	private function selectPlugin($plugins)
	{
		$pattern = '/^https:\/\/github\.com\/([^\/]+)\/([^\/]+)/';

		foreach ($plugins as $key => $plugin) {
			if(empty($plugin['repository'])) {
				unset($plugins[$key]);
				continue;
			}

			preg_match($pattern, $plugin['repository'], $matches);
			$author = $plugin['author']['name'] ?? '';
			$repoName = $matches[2] ?? '';
			$package = ($matches[1] ?? '').'/'.$repoName;

			$selectedPlugins[$key] = "{$plugin['title']} ({$repoName})".(!empty($author) ? " by {$author}" : '');
			$selededPluginsRaw[$key] = [
				'name' => $plugin['title'],
				'repo' => $repoName,
				'author' => $author,
				'package' => $package,
				'url' => $plugin['url'],
			];
		}

		$plugin = search(
			label: 'Search for the plugin you want to install:',
			hint: "Start typing to search for a plugin.",
			scroll: 10,
			options: fn(string $value) => strlen($value) > 0 ?
				array_filter($selectedPlugins, fn($plugin) => str_contains(strtolower($plugin), strtolower($value))) : [],
		);

		return $selededPluginsRaw[$plugin];
	}
}
