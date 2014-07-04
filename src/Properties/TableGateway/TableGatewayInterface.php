<?php namespace Properties\TableGateway;

use Properties\Property;

interface TableGatewayInterface
{
	public function create(Property $value);
	public function createOrUpdate(Property $value);
	public function update(Property $value);
}