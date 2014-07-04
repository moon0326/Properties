<?php namespace Properties\TableGateway;

use Properties\QueryBuilderInterface;
use Properties\IndexInterface;
use Properties\Property;

abstract class AbstractTableGateway implements TableGatewayInterface
{
	protected $queryBuilder;

	abstract protected function getTableName();

	public function __construct(QueryBuilderInterface $queryBuilder)
	{
		$this->queryBuilder = $queryBuilder;
	}

	public function create(Property $value)
	{
		$result = $this->queryBuilder->insert($this->getTableName(),[
			'index_id' => $value->indexId,
			'key'      => $value->key,
			'value'    => $value->value,
			'type'	   => $value->type
		]);

		return $result;
	}

	public function createOrUpdate(Property $value)
	{
		if ($value->id) {
			return $this->update($value);
		}

		return $this->create($value);
	}

	public function update(Property $value)
	{
		$result = $this->queryBuilder->update($this->getTableName(),[
			'key'      => $value->key,
			'value'    => $value->value
		], $value->id);

		return $result;
	}

	function findByIndexId($indexId)
	{
		$result = $this->queryBuilder->select($this->getTableName(), ['index_id'=>$indexId]);

		if (!is_array($result)) {
			return [];
		}

		return $result;
	}

}