<?php namespace Values\TableGateway;

use Values\QueryBuilderInterface;
use Values\IndexInterface;

abstract class AbstractTableGateway implements TableGatewayInterface
{
	protected $queryBuilder;
	protected $indexId;
	protected $key;
	protected $value;
	protected $id;

	abstract protected function getTableName();

	public function __construct(QueryBuilderInterface $queryBuilder, $indexId, $key, $value, $id = null)
	{
		$this->queryBuilder = $queryBuilder;
		$this->indexId = $indexId;
		$this->key = $key;
		$this->value = $value;
		$this->id = $id;
	}

	public function create()
	{
		$result = $this->queryBuilder->insert($this->getTableName(),[
			'index_id' => $this->indexId,
			'key'      => $this->key,
			'value'    => $this->value
		]);

		return $result;
	}

	public function createOrUpdate()
	{
		if ($this->id) {
			return $this->update();
		}

		return $this->create();
	}

	public function update()
	{
		$result = $this->queryBuilder->update($this->getTableName(),[
			'key'      => $this->key,
			'value'    => $this->value
		], $this->id);

		return $result;
	}
}