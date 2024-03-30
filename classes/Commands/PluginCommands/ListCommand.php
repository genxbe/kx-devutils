<?php

namespace X\Devutils\Commands\PluginCommands;

use X\Devutils\Lib\Plugins;
use X\Devutils\Commands\Command;

use Kirby\CLI\CLI;
use Kirby\Toolkit\Str;

use function Laravel\Prompts\error;
use function Laravel\Prompts\table;

class ListCommand extends Command
{
	public static string $commandName = 'x:plugins:list';
	public static string $commandDescription = 'List all installed plugins.';
	public static array $commandArgs = [];

	public function __construct(CLI $cli)
	{
		parent::__construct($cli);

        foreach($this->kirby->plugins() as $plugin)
        {
			$p = $this->kirby->plugin($plugin->name());

			$arr['name'] = $plugin->name();
			$arr['version'] = $p->version();
			$arr['descr'] = Str::short($p->description(), 100);

			$table[] = $arr;
        }

        if(empty($table))
        {
            return error('❌ There are no plugins installed.');
        }

		usort($table, function($a, $b) {
			return strcmp($a['name'], $b['name']);
		});

        table(
            headers: ['Name', 'Version', 'Description'],
            rows: $table,
        );
	}
}
