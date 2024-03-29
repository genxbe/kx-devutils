<?php

namespace X\Devutils\Lib;

use Kirby\Toolkit\Str;
use Kirby\Cms\Url;
use Kirby\Filesystem\F;
class Toolkit
{
	public static function flattenArray($array, $prefix = '')
	{
		$result = [];

		if(!is_array($array)) {
			return $array;
		}

		foreach ($array as $key => $value) {
			if ($value instanceof \Closure) {
				$result[$prefix . $key] = '-- Closure --';
				continue;
			}

			if (is_array($value)) {
				$result = array_merge($result, self::flattenArray($value, $prefix . $key . '.'));
			} else {
				$result[$prefix . $key] = $value;
			}
		}
		return $result;
	}

	public static function stringify($value)
	{
		return match($value) {
			null => 'null',
			true => 'true',
			false => 'false',
			'' => '-- empty string --',
			default => $value,
		};
	}

	public static function checkForMaintenance()
	{
		if (option('genxbe.kx-devutils.maintenance') === true) {
			$kirby = kirby();
			$rootFolder = $kirby->root();
			$panelUrl = option('panel.slug') ?? 'panel';

			if(!$kirby->user() && Str::position(Url::current(),$panelUrl) === false && F::exists($rootFolder.'/.maintenance'))
			{
				$email = file_get_contents($rootFolder.'/.maintenance');
				snippet('x/maintenance', compact('email'));
				die();
			}
		}
	}
}
