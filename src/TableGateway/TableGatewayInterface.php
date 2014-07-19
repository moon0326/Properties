<?php namespace Moon\Properties\TableGateway;

use Moon\Properties\Properties\PropertyInterface;

interface TableGatewayInterface
{
    public function create(PropertyInterface $value);
    public function createOrUpdate(PropertyInterface $value);
    public function update(PropertyInterface $value);
    public function delete(PropertyInterface $property);
    public function deleteByIndexId($indexId);
    public function findByIndexId($indexId);

}