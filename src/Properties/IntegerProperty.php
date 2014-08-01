<?php namespace Moon\Properties\Properties;

class IntegerProperty extends Property
{
	protected function decorateValue($value)
	{
		return intval($value);
	}

	public function getDataType()
	{
		return 'integer';
	}
}