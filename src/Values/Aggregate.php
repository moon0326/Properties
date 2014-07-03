<?php namespace Values;

use Values\Exceptions\KeyNotFoundException;
use Values\TableGateway\TableGatewayFactoryInterface;
use stdClass;

class Aggregate
{

	protected $tableName = 'values_aggregate';

	/**
	 * Dependencies
	 */
	protected $queryBuilder;
	protected $table;
	protected $index;
	protected $tableGatewayFactory;

	/**
	 * Valeus from values_aggregate
	 */
	protected $id;
	protected $name;
	protected $pk;
	protected $pkValue;
	protected $cachedValue;

	/**
	 * Popuplated value from the cache
	 */
	protected $values;

	protected $pendingValues = [];

	public function __construct(
		QueryBuilderInterface $queryBuilder,
		EntityInterface $entity,
		TableGatewayFactoryInterface $tableGatewayFactory
	)
	{
		$this->queryBuilder = $queryBuilder;
		$this->entity = $entity;
		$this->tableGatewayFactory = $tableGatewayFactory;

		$this->loadOrCreate();

	}

	protected function loadOrCreate()
	{
		$index = $this->queryBuilder->findOne(
			$this->tableName,
			[
				'name' => $this->entity->getName(),
				'pk' => $this->entity->getPrimaryKeyName(),
				'pk_value' => $this->entity->getPrimaryKeyValue()
			]
		);

		if (!$index) {
			$index = $this->createNewRecord();
		}

		$this->id           = $index->id;
		$this->name         = $index->name;
		$this->pk           = $index->pk;
		$this->pkValue     = $index->pk_value;
		$this->cachedValue = json_decode($index->cached_value);

		$this->populateCachedValue();

	}

	protected function populateCachedValue()
	{
		if (!$this->cachedValue) {
			$this->values = [];
			return;
		}

		foreach ($this->cachedValue as $key=>$value) {
			$this->values[$value->key] = new Value($value);
		}

	}

	protected function createNewRecord()
	{
		$index = $this->queryBuilder->findOne(
			$this->tableName,
			[
				'name' => $this->table->getName(),
				'pk' => $this->table->getPrimaryKeyName(),
				'pk_value' => $this->table->getPrimaryKeyValue()
			]
		);

		$index = $this->queryBuilder->findOne($table, ['id'=>$index]);
		return $index;
	}

	protected function addPendingValue($key, $value, $type = null, $id = null)
	{
		if (!$type) {
			$type = Helper::getDataType($value);
		}

		$values = new stdClass;
		$values->key = $key;
		$values->value = $value;
		$values->type = $type;
		$values->id = $id;
		$values->index_id = $this->pkValue;

		$this->pendingValues[$key] = new Value($values);
	}

	protected function update($key, $value, $type = null, $id = null)
	{
		if (!$this->has($key, $value)) {
			throw new KeyNotFoundException($key . ' doesn\'t exist. Please use set($key, $value, $type) to create it');
		}

		$this->addPendingValue($key, $value, $type, $id);
	}

	public function set($key, $value, $type = null)
	{
		if ($this->has($key)) {
			$this->update($key, $value, $type, $this->get($key, true)->id);
		} else {
			$this->addPendingValue($key, $value, $type);
		}
	}

	public function has($key)
	{
		return array_key_exists($key, $this->values);
	}

	public function get($key, $returnObject = false)
	{
		if (!$this->has($key)) {
			throw new KeyNotFoundException($key . ' doesn\'t exist. Please use set($key, $value, $type) to create it');
		}

		if ($returnObject) {
			return $this->values[$key];
		}

		return $this->values[$key]->value;
	}

	function persist()
	{
		$this->queryBuilder->beginTransaction();

		try {

			foreach ($this->pendingValues as $pendingValue) {

				$creator = $this->tableGatewayFactory->create(
					$this->queryBuilder,
					$this->pkValue,
					$pendingValue
				);

				$result = $creator->createOrUpdate();
			}

			$this->queryBuilder->commit();
			$this->rebuild();

		} catch (\Exception $e) {
			$this->queryBuilder->rollback();
			throw $e;
		}

		return true;

	}

	protected function rebuild()
	{
		$decimal = $this->queryBuilder->select('values_decimal', ['index_id'=>$this->id]);
		$int = $this->queryBuilder->select('values_int', ['index_id'=>$this->id]);
		$text = $this->queryBuilder->select('values_text', ['index_id'=>$this->id]);
		$varchar = $this->queryBuilder->select('values_varchar', ['index_id'=>$this->id]);

		if (!is_array($decimal)) $decimal = [];
		if (!is_array($int)) $int = [];
		if (!is_array($text)) $text = [];
		if (!is_array($varchar)) $varchar = [];

		$values = array_merge($decimal, $int, $text, $varchar);

		$index = new \stdClass;

		foreach ($values as $value) {
			$key = $value->key;
			$index->$key = new \stdClass;
			$index->$key->index_id = $value->index_id;
			$index->$key->key = $key;
			$index->$key->value = $value->value;
			$index->$key->id = $value->id;
		}

		$this->value = $index;
		$this->populateCachedValue();

		$this->queryBuilder->update($this->tableName, ['cached_value'=>json_encode($index)], $this->pkValue);

	}


}