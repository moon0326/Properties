<?php namespace Values\Facades\Laravel;

class Value
{

	public static function getInstance($table)
	{
		$queryBuilder = new QueryBuilder();

		return new \Values\Aggregate(
			$queryBuilder,
			$table,
			new \Values\Creator\Factory()
		);
	}

}

