<?php namespace Values\TableGateway;

use Values\Value;

interface TableGatewayInterface
{
	public function create(Value $value);
	public function createOrUpdate(Value $value);
	public function update(Value $value);
}