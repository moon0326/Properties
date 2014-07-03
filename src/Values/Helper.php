<?php namespace Values;

class Helper
{

	public static function getDataType($value)
	{
		if (is_numeric($value) && floor($value) != $value) {
			return 'Decimal';
		}

		if (is_float($value)) {
			return 'Float';
		}

		if (is_int($value)) {
			return 'Int';
		}

		if (strlen($value) >= 255) {
			return 'Text';
		}

		if (is_string($value)) {
			return 'Varchar';
		}

		throw new Exceptions\UnknownValueTypeException("Can't determine a value type for " . $value);
	}

}