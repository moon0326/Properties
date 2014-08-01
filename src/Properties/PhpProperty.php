<?php namespace Moon\Properties\Properties;

class PhpProperty extends Property
{
	protected function decorateValue($value)
	{
		if ($this->getId()) {
			return unserialize($value);
		} else {
			return serialize($value);
		}
	}

	public function getDataType()
	{
		return 'php';
	}
}