<?php namespace Values\Creator;

use Values\QueryBuilderInterface;
use Values\IndexInterface;

class Factory
{
	public function create($type, QueryBuilderInterface $queryBuilder, IndexInterface $table, $key, $value)
	{
		$classPath = "Values\\Creator\\" . $type;
		return new $classPath($queryBuilder, $table, $key, $value);
	}
}