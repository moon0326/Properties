<?php namespace Moon\Properties\TableGateways;

class Integer extends TableGateway
{
    protected function getTableName()
    {
        return 'properties_integer';
    }
}