<?php namespace Moon\Properties\TableGateways;

use Moon\Properties\Properties\PropertyInterface;

class Php extends AbstractTableGateway
{
    protected function getTableName()
    {
        return 'properties_text';
    }
}