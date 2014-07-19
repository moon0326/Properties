<?php namespace Moon\Properties\TableGateways;

class Text extends AbstractTableGateway
{
    protected function getTableName()
    {
        return 'properties_text';
    }
}