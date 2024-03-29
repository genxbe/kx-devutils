<?php

namespace X\Commands\Commands;

use Kirby\Cms\App;
use Kirby\CLI\CLI;

class Command
{
	protected CLI $cli;
	protected App $kirby;

	public function __construct(CLI $cli)
	{
		$this->cli = $cli;
		$this->kirby = App::instance();
	}
}
