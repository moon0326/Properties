<?php namespace Moon\Properties\TableGateways;

class Decimal extends TableGateway
{
    protected function getTableName()
    {
        return 'properties_decimal';
    }
}
