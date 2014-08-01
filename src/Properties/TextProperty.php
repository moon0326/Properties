<?php namespace Moon\Properties\Properties;

class TextProperty extends Property
{
	protected function decorateValue($value)
	{
		return $value;
	}

	public function getDataType()
	{
		return 'text';
	}
}