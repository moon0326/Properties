<?php namespace Values\Creator;

class Int extends AbstractCreator
{
	protected function getTableName()
	{
		return 'values_int';
	}
}