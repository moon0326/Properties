<?php namespace Moon\Properties\TableGateway;

class Decimal extends AbstractTableGateway
{
    protected function getTableName()
    {
        return 'properties_decimal';
    }
}
