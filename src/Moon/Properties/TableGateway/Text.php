<?php namespace Moon\Properties\TableGateway;

class Text extends AbstractTableGateway
{
	protected function getTableName()
	{
		return 'values_text';
	}
}