<?php namespace Moon\Properties\TableGateway;

use Moon\Properties\Property;

interface TableGatewayInterface
{
    public function create(Property $value);
    public function createOrUpdate(Property $value);
    public function update(Property $value);
    public function delete(Property $property);

}