<?php namespace Values\TableGateway;

use Values\QueryBuilderInterface;
use Values\IndexInterface;
use Values\Value;

class TableGatewayFactory implements TableGatewayFactoryInterface
{
	protected $basePath = "Values\\TableGateway\\";

	public function create(QueryBuilderInterface $queryBuilder, $type)
	{
		$classPath = $this->basePath . $type;
		return new $classPath($queryBuilder);
	}

	function createByType(QueryBuilderInterface $queryBuilder)
	{
		$classPath = $this->basePath . $type;
		return new $classPath;
	}
}