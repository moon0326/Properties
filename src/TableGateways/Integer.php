<?php namespace Moon\Properties\TableGateways;

class Integer extends AbstractTableGateway
{
    protected function getTableName()
    {
        return 'properties_integer';
    }
}