<?php

namespace X\Devutils\Lib;

use Kirby\Toolkit\A;
use Kirby\Toolkit\Str;

use function Laravel\Prompts\suggest;

class Plugins
{
	private $kirby;

	public function __construct($kirby)
	{
		$this->kirby = $kirby;
	}

	public function selectPlugin()
	{
		$plugins = $this->kirby->plugins();
		$fullPluginNames = array_keys(array_map(function($plugin) {
			return $plugin->name();
		}, $plugins));

		foreach($fullPluginNames as $plugin) {
			$pluginParts = Str::split($plugin, '/');
			$pluginNames[] = $pluginParts[1]." ({$plugin})";
		}

		$safePluginNames = A::merge($fullPluginNames, $pluginNames);

		$selectedPlugin = suggest(
			label: 'Do you want options for a specific plugin? (Leavy empty for all)',
			options: $safePluginNames,
			placeholder: 'E.g. ray or bnomei/... (or just use the down arrow to select)',
		);

		return $selectedPlugin;
	}
}
