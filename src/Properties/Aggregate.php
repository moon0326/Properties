<?php namespace Properties;

use Properties\Exceptions\KeyNotFoundException;
use Properties\TableGateway\TableGatewayFactoryInterface;
use stdClass;

class Aggregate
{
    /**
     * @var string name of the aggregate table
     */
    protected $tableName = 'values_aggregate';

    /**
     * @var Values\QueryBuilderInterface
     */
    protected $queryBuilder;

    /**
     * @var Values\EntityInterface
     */
    protected $entity;

    /**
     * @var Values\TableGateway\TableGatewayFactoryInterface
     */
    protected $tableGatewayFactory;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $pk;

    /**
     * @var int
     */
    protected $pkValue;

    /**
     * @var string json encoded values
     */
    protected $cachedValue;

    /**
     * @var Array popuplatd values from $cachedValue
     */
    protected $values;

    /**
     * @var Array
     */
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

    /**
     * Create a new row in $tableName
     * or loads an existing row
     * @return Void
     */
    protected function loadOrCreate()
    {
        $index = $this->queryBuilder->selectFirst(
            $this->tableName,
            [
                'name' => $this->entity->getName(),
                'pk' => $this->entity->getIdentifierName(),
                'pk_value' => $this->entity->getIdentifier()
            ]
        );

        if (!$index) {
            $index = $this->createNewRecord();
        }

		$this->id          = $index->id;
		$this->name        = $index->name;
		$this->pk          = $index->pk;
		$this->pkValue     = $index->pk_value;
		$this->cachedValue = json_decode($index->cached_value);

        $this->populateCachedValue();
    }

    /**
     * Decode cached json and generate Value objects from it
     * @return Void
     */
    protected function populateCachedValue()
    {
        if (!$this->cachedValue) {
            $this->values = [];

            return;
        }

        foreach ($this->cachedValue as $key=>$value) {
            $this->values[$value->key] = new Property($value);
        }
    }

    protected function createNewRecord()
    {
        $index = $this->queryBuilder->selectFirst(
            $this->tableName,
            [
                'name' => $this->table->getName(),
                'pk' => $this->table->getIdentifierName(),
                'pk_value' => $this->table->getIdentifier()
            ]
        );

        $index = $this->queryBuilder->selectFirst($table, ['id'=>$index]);

        return $index;
    }

    /**
     * Add a temporary Value object to the pendingValues stack
     * Determins a data type for the given value if not given
     * @return Void
     */
    protected function addPendingValue($key, $value, $type = null, $id = null)
    {
        if (!$type) {
            $type = gettype($value);

            if ($type === 'string') {
                $type = 'varchar';
            }

            if ($type === 'varchar' && strlen($value) >= 255) {
                $type = 'text';
            }

            if ($type === 'array' || $type === 'object') {
                $type = 'php';
            }

            $type = ucfirst($type);
        }

        $values = new stdClass;
        $values->key = $key;
        $values->value = $value;
        $values->type = $type;
        $values->id = $id;
        $values->index_id = $this->pkValue;

        $this->pendingValues[$key] = new Property($values);
    }

    /**
     * Updates an exsiting value by its key
     * @return Void
     */
    protected function update($key, $value, $type = null, $id = null)
    {
        if (!$this->has($key, $value)) {
            throw new KeyNotFoundException($key . ' doesn\'t exist. Please use set($key, $value, $type) to create it');
        }

        $this->addPendingValue($key, $value, $type, $id);
    }

    /**
     * Sets a new key/value pair or updates an existing row
     * @return Boolean
     */
    public function set($key, $value, $type = null)
    {
        if ($this->has($key)) {
            $this->update($key, $value, $type, $this->get($key, true)->id);
        } else {
            $this->addPendingValue($key, $value, $type);
        }

        return true;
    }

    /**
     * See if a key exists
     * @return Boolean
     */
    public function has($key)
    {
        return array_key_exists($key, $this->values);
    }

    /**
     * Return a value by its key
     * @param  string      $key
     * @param  boolean     $returnObject
     * @return Value|mixed if @returnObject is set, a Value object is returned
     */
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

    /**
     * Insert/Update any existing pending Value objects
     * @return Boolean
     */
    public function save()
    {
        $this->queryBuilder->beginTransaction();

        try {

            foreach ($this->pendingValues as $pendingValue) {
                $creator = $this->tableGatewayFactory->create($this->queryBuilder, $pendingValue->type);
                $result = $creator->createOrUpdate($pendingValue);
            }

            $this->queryBuilder->commit();
            $this->rebuild();

        } catch (\Exception $e) {
            $this->queryBuilder->rollback();
            throw $e;
        }

        $this->pendingValues = [];

        return true;
    }

    /**
     * Rebuild a cached_value value
     */
    public function rebuild()
    {
        $types = ['Decimal', 'Integer', 'Text', 'Varchar'];

        foreach ($types as $type) {
            $tableGateway = $this->tableGatewayFactory->create($this->queryBuilder, $type);
            $values[] = $tableGateway->findByIndexId($this->id);
        }

        $values = array_merge($values[0], $values[1], $values[2], $values[3]);

        $index = new stdClass;

        foreach ($values as $value) {
            $key = $value->key;
            $index->$key = new stdClass;
            $index->$key->index_id = $value->index_id;
            $index->$key->key = $key;
            $index->$key->value = $value->value;
            $index->$key->id = $value->id;
            $index->$key->type = $value->type;
        }

        $this->value = $index;
        $this->populateCachedValue();

        $this->queryBuilder->update($this->tableName, ['cached_value'=>json_encode($index)], $this->pkValue);

    }
}
