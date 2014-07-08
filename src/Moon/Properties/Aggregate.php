<?php namespace Moon\Properties;

use Moon\Properties\Exceptions\KeyNotFoundException;
use Moon\Properties\TableGateway\TableGatewayFactoryInterface;
use stdClass;

class Aggregate
{
    /**
     * @var string name of the aggregate table
     */
    protected $tableName = 'properties_aggregate';

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
        $index = $this->queryBuilder->insert(
            $this->tableName,
            [
                'name' => $this->entity->getName(),
                'pk' => $this->entity->getIdentifierName(),
                'pk_value' => $this->entity->getIdentifier()
            ]
        );

        $index = $this->queryBuilder->selectFirst($this->tableName, ['id'=>$index]);

        return $index;
    }

    protected function getDataType($value)
    {
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

        return ucfirst($type) ;
    }

    /**
     * Add a temporary Value object to the pendingValues stack
     * Determins a data type for the given value if not given
     * @return Void
     */
    protected function addPendingValue($key, $value, $type = null, $operation, $id = null)
    {
        if (!$type) {
            $type = $this->getDataType($value);
        }

        $values = new stdClass;
        $values->key = $key;
        $values->value = $value;
        $values->type = $type;
        $values->id = $id;
        $values->index_id = $this->id;

        $this->pendingValues[$key] = [$operation, new Property($values)];
    }

    /**
     * Updates an exsiting value by its key
     * @return Void
     */
    protected function update($key, $value, $type = null, $operation, $id = null)
    {
        if (!$this->has($key, $value)) {
            throw new KeyNotFoundException($key . ' doesn\'t exist. Please use set($key, $value, $type) to create it');
        }

        $this->addPendingValue($key, $value, $type, $operation, $id);
    }

    /**
     * Sets a new key/value pair or updates an existing row
     * @return Boolean
     */
    public function set($key, $value, $type = null)
    {
        if ($this->has($key)) {
            $this->update($key, $value, $type, 'update', $this->get($key, true)->id);
        } else {
            $this->addPendingValue($key, $value, $type, 'create');
        }

        return true;
    }

    public function delete($key)
    {
        if (!$this->has($key)) {
            throw new KeyNotFoundException($key . ' doesn\'t exist. You can\'t delete it');
        }

        $property = $this->get($key, true);
        $property->type = $this->getDataType($property->value);

        $this->pendingValues[$key] = ['delete', $property];
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

    public function all()
    {
        return $this->values;
    }

    public function getPendingProperties()
    {
        return $this->pendingValues;
    }

    /**
     * Returns a list of keys
     * @return  Array
     */
    public function keys()
    {
        return array_keys($this->values);
    }

    /**
     * Insert/Update any existing pending Value objects
     * @return Boolean
     */
    public function save()
    {

        try {

            foreach ($this->pendingValues as $pendingValue) {

                $operation = $pendingValue[0];
                $property = $pendingValue[1];
                $tableGateway = $this->tableGatewayFactory->create($this->queryBuilder, $property->type);

                switch ($operation) {
                    case 'update':
                    case 'create':
                        $result = $tableGateway->createOrUpdate($property);
                        break;
                    case 'delete':
                        $result = $tableGateway->delete($property);
                        break;
                }
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

        if (count($values)) {

            $index = new stdClass;
            foreach ($values as $value) {
                $index->{$value->key} = new Property($value);
            }

        } else {
            $index = null;
        }

        $this->cachedValue = $index;
        $this->populateCachedValue();

        $this->queryBuilder->update(
            $this->tableName,
            ['cached_value'=>json_encode($index)],
            $this->id
        );

    }
}
