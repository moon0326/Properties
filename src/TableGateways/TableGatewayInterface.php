<?php namespace Moon\Properties\TableGateways;

use Moon\Properties\Properties\Property;

interface TableGatewayInterface
{
    public function create(Property $value);
    public function createOrUpdate(Property $value);
    public function update(Property $value);
    public function delete(Property $property);
    public function deleteByIndexId($indexId);
    public function findByIndexId($indexId);
}