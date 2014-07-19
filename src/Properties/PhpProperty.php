<?php namespace Moon\Properties\Properties;

class PhpProperty extends AbstractProperty
{
	protected function decorateValue($value)
	{
		return unserialize($value);
	}

	public function getDataType()
	{
		return 'php';
	}
}