<?php namespace Moon\Properties\TableGateways;

use Moon\Properties\QueryBuilderInterface;

class TableGatewayFactory
{
    public function create(QueryBuilderInterface $queryBuilder, $dataType)
    {
        $classPath = "Moon\\Properties\\TableGateways\\" . ucfirst($dataType);
        return new $classPath($queryBuilder);
    }
}