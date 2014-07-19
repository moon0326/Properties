<?php namespace Moon\Properties\Properties;

class IntegerProperty extends AbstractProperty
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