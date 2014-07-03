<?php namespace Values\TableGateway;

class Varchar extends AbstractTableGateway
{
	protected function getTableName()
	{
		return 'values_varchar';
	}
}