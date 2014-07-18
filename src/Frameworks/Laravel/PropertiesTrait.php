<?php namespace Moon\Properties\Frameworks\Laravel;

use Moon\Properties\Frameworks\Laravel\QueryBuilder;
use Moon\Properties\Aggregate;
use Moon\Properties\TableGateway\TableGatewayFactory;

trait PropertiesTrait
{

    private $_aggregate = null;

    public function getIdentifierName()
    {
        return 'id';
    }

    public function getIdentifier()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->table;
    }

    public function getProperties()
    {
        if ($this->_aggregate === null) {
            $queryBuilder = new QueryBuilder();

            $this->_aggregate = new Aggregate(
                $queryBuilder,
                $this,
                new TableGatewayFactory()
            );
        }

        return $this->_aggregate;
    }
}