<?php namespace Moon\Properties\TableGateway;

use Moon\Properties\QueryBuilderInterface;
use Moon\Properties\IndexInterface;
use Moon\Properties\Value;

interface TableGatewayFactoryInterface
{
    public function create(QueryBuilderInterface $queryBuilder, $type);
}