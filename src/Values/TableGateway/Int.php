<?php namespace Values\TableGateway;

class Int extends AbstractTableGateway
{
	protected function getTableName()
	{
		return 'values_int';
	}
}