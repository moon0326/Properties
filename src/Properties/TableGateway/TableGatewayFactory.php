<?php namespace Properties\TableGateway;

use Properties\QueryBuilderInterface;
use Properties\IndexInterface;
use Properties\Value;

class TableGatewayFactory implements TableGatewayFactoryInterface
{
	protected $basePath = "Properties\\TableGateway\\";

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