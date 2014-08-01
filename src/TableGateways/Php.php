<?php namespace Moon\Properties\TableGateways;

use Moon\Properties\Properties\PropertyInterface;

class Php extends TableGateway
{
    protected function getTableName()
    {
        return 'properties_text';
    }
}