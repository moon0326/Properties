<?php namespace Moon\Properties\TableGateway;

use Moon\Properties\QueryBuilderInterface;
use Moon\Properties\IndexInterface;
use Moon\Properties\Value;
use Moon\Properties\Properties\PropertyInterface;

class TableGatewayFactory
{
    protected $basePath = "Moon\Properties\\TableGateway\\";

    public function create(QueryBuilderInterface $queryBuilder, $dataType)
    {
        $classPath = $this->basePath . ucfirst($dataType);
        return new $classPath($queryBuilder);
    }
}