<?php namespace Moon\Properties\TableGateways;

class Varchar extends TableGateway
{
    protected function getTableName()
    {
        return 'properties_varchar';
    }
}