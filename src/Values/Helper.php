<?php namespace Values;

class Helper
{

	public static function getDataType($value)
	{

		$type = gettype($value);

		if ($type === 'string') {
			$type = 'varchar';
		}

		if ($type === 'varchar' && strlen($value) >= 255) {
			$type = 'text';
		}

		return ucfirst($type);

		throw new Exceptions\UnknownValueTypeException("Can't determine a value type for " . $value);
	}

}