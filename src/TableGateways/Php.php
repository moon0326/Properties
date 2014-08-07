<?php namespace Moon\Properties\TableGateways;

class Php extends TableGateway
{
    protected function getTableName()
    {
        return 'properties_text';
    }
}