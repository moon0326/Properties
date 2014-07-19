<?php namespace Moon\Properties\TableGateways;

class Decimal extends AbstractTableGateway
{
    protected function getTableName()
    {
        return 'properties_decimal';
    }
}
