<?php namespace Moon\Properties\Properties;

class VarcharProperty extends Property
{
	protected function decorateValue($value)
	{
		return $value;
	}

	public function getDataType()
	{
		return 'varchar';
	}
}