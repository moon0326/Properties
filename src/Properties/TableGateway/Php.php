<?php namespace Properties\TableGateway;

use Properties\Property;

class Php extends AbstractTableGateway
{
	protected function getTableName()
	{
		return 'values_text';
	}

	public function createOrUpdate(Property $value)
	{
		$value->value = serialize($value->value);

		if ($value->id) {
			return $this->update($value);
		}

		return $this->create($value);
	}
}