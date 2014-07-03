<?php namespace Values\TableGateway;

use Values\QueryBuilderInterface;
use Values\IndexInterface;

class TableGatewayFactory implements TableGatewayFactoryInterface
{
	public function create($type, QueryBuilderInterface $queryBuilder, $indexId, $key, $value, $id = null)
	{
		$classPath = "Values\\TableGateway\\" . $type;
		return new $classPath($queryBuilder, $indexId, $key, $value, $id);
	}
}