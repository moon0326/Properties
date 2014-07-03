<?php namespace Values\TableGateway;

use Values\QueryBuilderInterface;
use Values\IndexInterface;

interface TableGatewayFactoryInterface
{
	public function create($type, QueryBuilderInterface $queryBuilder, $indexId, $key, $value, $id = null);
}