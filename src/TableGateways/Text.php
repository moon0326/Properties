<?php namespace Moon\Properties\TableGateways;

class Text extends TableGateway
{
    protected function getTableName()
    {
        return 'properties_text';
    }
}