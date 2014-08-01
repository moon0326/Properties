<?php namespace Moon\Properties\Properties;

class DecimalProperty extends Property
{
	protected function decorateValue($value)
	{
		/**
		* Note that using floatval() drops the precisions if you have .00
		*/
		return floatval($value);
	}

	public function getDataType()
	{
		return 'decimal';
	}
}