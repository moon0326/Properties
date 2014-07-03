<?php namespace Values\Creator;

class Varchar extends AbstractCreator
{
	protected function getTableName()
	{
		return 'values_varchar';
	}
}