<?php namespace Moon\Properties\TableGateway;

use Moon\Properties\QueryBuilderInterface;
use Moon\Properties\IndexInterface;
use Moon\Properties\Value;

class TableGatewayFactory implements TableGatewayFactoryInterface
{
	protected $basePath = "Moon\Properties\\TableGateway\\";

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