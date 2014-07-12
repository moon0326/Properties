<?php namespace Moon\Properties\Frameworks\Kohana;

use Moon\Properties\Facades\Kohana\QueryBuilder;
use Moon\Properties\Aggregate;
use Moon\Properties\TableGateway\TableGatewayFactory;

trait PropertiesTrait
{
    public function getIdentifierName()
    {
        return 'id';
    }

    public function getIdentifier()
    {
        return $this->pk();
    }

    public function getName()
    {
        return $this->table_name();
    }

    public function getProperties()
    {
        $queryBuilder = new QueryBuilder();

        return new Aggregate(
            $queryBuilder,
            $this,
            new TableGatewayFactory()
        );
    }
}