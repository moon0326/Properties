<?php namespace Values\TableGateway;

use Values\QueryBuilderInterface;
use Values\IndexInterface;
use Values\Value;

interface TableGatewayFactoryInterface
{
	public function create(QueryBuilderInterface $queryBuilder, $type);
}