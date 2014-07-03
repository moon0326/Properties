<?php namespace Values;

use Values\Exceptions\NotOverridableException;
use Values\Index;

class Aggregate
{

	protected $queryBuilder;
	protected $table;
	protected $index;
	protected $creatorFactory;

	protected $values = [];
	protected $pendingValues = [];

	public function __construct(
		QueryBuilderInterface $queryBuilder,
		TableInterface $table,
		$creatorFactory
	)
	{
		$this->queryBuilder = $queryBuilder;
		$this->table = $table;
		$this->creatorFactory = $creatorFactory;

		$index = $this->queryBuilder->select(
			$this->getIndexTableName(),
			[
				'table_name' => $this->table->getName(),
				'table_pk_name' => $this->table->getPrimaryKeyName(),
				'table_pk_value' => $this->table->getPrimaryKeyValue()
			]
		);

		$index = $index[0];

		if (!$index) {
			$index = $this->createIndex();
		}

		$this->index = new Index((array)$index);

	}

	protected function createIndex()
	{
		$index = $this->queryBuilder->insert(
			$this->getIndexTableName(),
			[
				'table_name' => $this->table->getName(),
				'table_pk_name' => $this->table->getPrimaryKeyName(),
				'table_pk_value' => $this->table->getPrimaryKeyValue()
			]
		);

		$index = $this->queryBuilder->select($table, ['id'=>$index]);
		return $index[0];
	}

	protected function getIndexTableName()
	{
		return 'values_index';
	}

	protected function hasKey($key)
	{
		return array_key_exists($key, $this->values);
	}

	protected function getValueType($value)
	{

		if (is_numeric($value) && floor($value) != $value) {
			return 'Decimal';
		}

		if (is_float($value)) {
			return 'Float';
		}

		if (is_int($value)) {
			return 'Int';
		}

		if (strlen($value) >= 255) {
			return 'Text';
		}

		if (is_string($value)) {
			return 'Varchar';
		}

		throw new Exceptions\UnknownValueTypeException("Can't determine a value type for " . $value);
	}

	protected function addPendingValue($key, $value, $type = null, $action)
	{
		if (!$type) {
			$type = $this->getValueType($value);
		}

		$this->pendingValues[$key] = [
			'key'    => $key,
			'value'  => $value,
			'type'   => $type,
			'action' => $action
		];
	}

	protected function overrideOrInsertExistingValue($key, $value)
	{
		$this->values[$key] = $value;
	}

	public function update($key, $value, $type = null)
	{
		if (!$this->hasKey($key, $value)) {
			throw new KeyNotFoundException($key . ' doesn\'t exist. Please use set($key, $value, $type) to create it');
		}

		$this->addPendingValue($key, $value, $type, 'Update');
	}

	public function set($key, $value, $type = null)
	{
		if ($this->hasKey($key)) {
			throw new NotOverridableException($key . ' exists. Please use update($key, $value, $valueType) method');
		}

		$this->addPendingValue($key, $value, $type, 'Insert');
	}

	public function get($key)
	{
		if (!$this->hasKey($key)) {
			throw new KeyNotFoundException($key . ' doesn\'t exist. Please use set($key, $value, $type) to create it');
		}

		return $this->values[$key];
	}

	function persist()
	{
		// $this->queryBuilder->beginTransaction();
		try {

			foreach ($this->pendingValues as $pendingValue) {

				$creator = $this->creatorFactory->create(
					$pendingValue['type'],
					$this->queryBuilder,
					$this->index,
					$pendingValue['key'],
					$pendingValue['value']
				);

				$result = $creator->create();

			}

		} catch (\Exception $e) {
			// $this->queryBuilder->rollback();
			throw $e;
		}

		return true;

	}



}