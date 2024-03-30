<?php

namespace X\Devutils\Commands\KirbyCommands;

use Kirby\CLI\CLI;
use Kirby\Toolkit\Str;
use X\Devutils\Lib\Toolkit;
use X\Devutils\Lib\Plugins;
use X\Devutils\Commands\Command;

use function Laravel\Prompts\info;
use function Laravel\Prompts\table;
use function Laravel\Prompts\error;

class OptionsCommand extends Command
{
	public static string $commandName = 'x:options';
	public static string $description = 'Show all options or select a plugin to show its options';
	public static array $commandArgs = [
		'--all' => [
			'longPrefix'  => 'all',
			'description' => 'Show all kirby and plugin options',
			'noValue'     => true,
		],
	];

	public function __construct(CLI $cli)
	{
		parent::__construct($cli);

		if($cli->arg('--all')) {
			return $this->getOptions();
		}

		$plugins = new Plugins($this->kirby);
		$plugin = $plugins->selectPlugin();
		$this->getOptions($plugin);
	}

	private function getOptions(string $plugin = null)
	{
		$allOptions = $this->kirby->options();

		if(!empty($plugin)) {
			if(!empty($allOptions[Str::replace($plugin, '/', '.')])) {
				$pluginOptions = $allOptions[Str::replace($plugin, '/', '.')];
				return $this->printOptions($plugin, $pluginOptions);
			}

			return error('❌ Plugin not found.');
		}

		return $this->printAllOptions($allOptions);
	}

	private function printOptions(string $plugin, array $pluginOptions)
	{
		$pluginOptions = Toolkit::flattenArray($pluginOptions);

		$table = [];
		foreach($pluginOptions as $option => $default)
		{
			$default = Toolkit::stringify($default);

			array_push($table, [
				'option' => $option,
				'default' => $default,
			]);
		}

		table(
			headers: ['Option', 'Default'],
			rows: $table,
		);
	}

	private function printAllOptions(array $allOptions)
	{
		foreach($allOptions as $group => $options) {
			$groupOptions = Toolkit::flattenArray($options);

			if(is_array($groupOptions)) {
				$table = [];

				foreach($groupOptions as $option => $default) {
					array_push($table, [
						'group' => $group,
						'option' => $option,
						'default' => $default,
					]);
				}

				if(empty($table)) {
					continue;
				}

				info($group);

				table(
					headers: ['Option', 'Default'],
					rows: array_map(function($item) {
						return [
							$item['option'],
							Str::short(Toolkit::stringify($item['default']), 50),
						];
					}, $table),
				);
			}

			if(!is_array($groupOptions)) {
				if($groupOptions instanceof \Closure) {
					$groupOptions = '-- Closure --';
				}

				info($group);

				table(
					headers: ['Option', 'Default'],
					rows: [
						[$group, Toolkit::stringify($groupOptions)],
					],
				);
			}
		}
	}
}
