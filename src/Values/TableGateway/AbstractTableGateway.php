<?php namespace Values\TableGateway;

use Values\QueryBuilderInterface;
use Values\IndexInterface;
use Values\Value;

abstract class AbstractTableGateway implements TableGatewayInterface
{
	protected $queryBuilder;

	abstract protected function getTableName();

	public function __construct(QueryBuilderInterface $queryBuilder)
	{
		$this->queryBuilder = $queryBuilder;
	}

	public function create(Value $value)
	{
		$result = $this->queryBuilder->insert($this->getTableName(),[
			'index_id' => $value->index_id,
			'key'      => $value->key,
			'value'    => $value->value
		]);

		return $result;
	}

	public function createOrUpdate(Value $value)
	{
		if ($value->id) {
			return $this->update($value);
		}

		return $this->create($value);
	}

	public function update(Value $value)
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