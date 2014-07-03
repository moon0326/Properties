<?php namespace Values\TableGateway;

interface TableGatewayInterface
{
	public function create();
	public function createOrUpdate();
	public function update();
}