<?php

namespace X\Commands\Commands;

use Closure;
use ReflectionFunction;

use Kirby\CLI\CLI;
use Kirby\Toolkit\Str;

use function Laravel\Prompts\table;

class Routes extends Command
{
	public function __construct(CLI $cli)
	{
		parent::__construct($cli);

		$routes = $this->kirby->routes();

		foreach($routes as $route) {
			$parse[$route['env'] ?? 'custom'][$route['method'] ?? 'GET'][] = [
				'pattern' => $route['pattern'],
				'language' => $route['language'] ?? '',
				'closure' => $this->getClosureMeta($route['action']),
			];
		}

		$table = [];
		foreach($parse as $env => $envs) {
			foreach($envs as $method => $routes) {
				foreach($routes as $key => $meta) {
					if(empty($meta['pattern'])) {
						continue;
					}

					array_push($table, [
						$meta['language'],
						$meta['pattern'],
						$env,
						$method,
						$meta['closure']['filename'],
					]);
				}
			}
		}

		table(
			headers: ['Language', 'Pattern', 'Env', 'Method', 'Filename'],
			rows: $table,
		);
	}

	private function getClosureMeta(Closure $closure)
	{
		$ref = new ReflectionFunction($closure);
		$filename = Str::replace(
			$ref->getFileName(),
			$this->kirby->root(),
			'',
		);

		return [
			'filename' => $filename,
		];
	}
}
