<?php namespace Moon\Properties\TableGateways;

use Moon\Properties\Property;

class Php extends AbstractTableGateway
{
    protected function getTableName()
    {
        return 'properties_text';
    }

    public function createOrUpdate(Property $value)
    {
        $value->value = serialize($value->value);
        parent::createOrUpdate($value);
    }
}