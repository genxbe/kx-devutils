<?php

namespace X\Commands\Lib;

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
}
