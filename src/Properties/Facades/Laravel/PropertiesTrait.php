<?php namespace Properties\Facades\Laravel;

use Properties\Facades\Laravel\QueryBuilder;
use Properties\Aggregate;
use Properties\TableGateway\TableGatewayFactory;

trait PropertiesTrait
{
	public function getIdentifierName()
	{
		return 'id';
	}

	public function getIdentifier()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->table;
	}

	public function getProperties()
	{

		$queryBuilder = new QueryBuilder();

		return new Aggregate(
			$queryBuilder,
			$this,
			new TableGatewayFactory()
		);

	}
}