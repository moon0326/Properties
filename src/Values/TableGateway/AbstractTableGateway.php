<?php namespace Values\TableGateway;

use Values\QueryBuilderInterface;
use Values\IndexInterface;
use Values\Value;

abstract class AbstractTableGateway implements TableGatewayInterface
{
	protected $queryBuilder;
	protected $indexId;
	protected $value;

	abstract protected function getTableName();

	public function __construct(QueryBuilderInterface $queryBuilder, $indexId, Value $value)
	{
		$this->queryBuilder = $queryBuilder;
		$this->indexId = $indexId;
		$this->value = $value;
	}

	public function create()
	{
		$result = $this->queryBuilder->insert($this->getTableName(),[
			'index_id' => $this->indexId,
			'key'      => $this->value->key,
			'value'    => $this->value->value
		]);

		return $result;
	}

	public function createOrUpdate()
	{
		if ($this->value->id) {
			return $this->update();
		}

		return $this->create();
	}

	public function update()
	{
		$result = $this->queryBuilder->update($this->getTableName(),[
			'key'      => $this->value->key,
			'value'    => $this->value->value
		], $this->value->id);

		return $result;
	}
}