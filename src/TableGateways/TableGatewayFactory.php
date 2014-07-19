<?php namespace Moon\Properties\TableGateways;

use Moon\Properties\QueryBuilderInterface;
use Moon\Properties\IndexInterface;
use Moon\Properties\Value;
use Moon\Properties\Properties\PropertyInterface;

class TableGatewayFactory
{
    public function create(QueryBuilderInterface $queryBuilder, $dataType)
    {
        $classPath = "Moon\\Properties\\TableGateways\\" . ucfirst($dataType);
        return new $classPath($queryBuilder);
    }
}