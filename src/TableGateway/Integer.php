<?php namespace Moon\Properties\TableGateway;

class Integer extends AbstractTableGateway
{
    protected function getTableName()
    {
        return 'properties_integer';
    }
}