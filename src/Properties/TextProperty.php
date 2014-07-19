<?php namespace Moon\Properties\Properties;

class TextProperty extends AbstractProperty
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