<?php namespace Moon\Properties\TableGateways;

class Varchar extends AbstractTableGateway
{
    protected function getTableName()
    {
        return 'properties_varchar';
    }
}