<?php namespace Moon\Properties\TableGateway;

class Double extends AbstractTableGateway
{
    protected function getTableName()
    {
        return 'properties_decimal';
    }
}
