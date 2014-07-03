<?php namespace Values\TableGateway;

use Values\QueryBuilderInterface;
use Values\IndexInterface;
use Values\Value;

class TableGatewayFactory implements TableGatewayFactoryInterface
{
	public function create(QueryBuilderInterface $queryBuilder, $indexId, Value $value)
	{
		$classPath = "Values\\TableGateway\\" . $value->type;
		return new $classPath($queryBuilder, $indexId, $value);
	}
}