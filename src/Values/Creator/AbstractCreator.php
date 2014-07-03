<?php namespace Values\Creator;

use Values\QueryBuilderInterface;
use Values\IndexInterface;

abstract class AbstractCreator
{
	protected $queryBuilder;
	protected $index;
	protected $key;
	protected $value;

	abstract protected function getTableName();

	public function __construct(QueryBuilderInterface $queryBuilder, IndexInterface $index, $key, $value)
	{
		$this->queryBuilder = $queryBuilder;
		$this->index = $index;
		$this->key = $key;
		$this->value = $value;
	}

	public function create()
	{
		$result = $this->queryBuilder->insert($this->getTableName(),[
			'index_id' => $this->index->getId(),
			'key'      => $this->key,
			'value'    => $this->value
		]);

		return $result;
	}
}