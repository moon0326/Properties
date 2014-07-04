<?php namespace Properties\TableGateway;

use Properties\QueryBuilderInterface;
use Properties\IndexInterface;
use Properties\Value;

interface TableGatewayFactoryInterface
{
	public function create(QueryBuilderInterface $queryBuilder, $type);
}