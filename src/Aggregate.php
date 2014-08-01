<?php namespace Moon\Properties;

use Moon\Properties\Exceptions\KeyNotFoundException;
use Moon\Properties\TableGateways\TableGatewayFactory;
use Moon\Properties\Properties\PropertyFactory;
use stdClass;
use Countable;

/**
 * An aggreagte for the properties
 * Takes care of operations for the beloging properties
 */
class Aggregate implements Countable
{
    /**
     * Indicates whether a record was loaded from properties_aggregate or not.
     * As of now, this value is only useful for checking the state of Aggregate object when destroy() is called.
     * When a user tries to call set, update, or delete methods after calling destroy(),
     * has($key) checks to see if $loaded is set to true. Otherwise, DomainException exception will be thrown
     * @todo  find a better way of handling the state after destory()
     * @var boolean
     */
    protected $loaded = false;

    /**
     * @var string name of the aggregate table
     */
    protected $tableName = 'properties_aggregate';

    /**
     * @var Array Supported data types
     */
    protected $supportedDataTypes = ['decimal', 'integer', 'text', 'varchar'];

    /**
     * @var Properties\QueryBuilderInterface
     */
    protected $queryBuilder;

    /**
     * @var Properties\EntityInterface
     */
    protected $entity;

    /**
     * @var Properties\TableGateway\TableGatewayFactory
     */
    protected $tableGatewayFactory;

    /**
     * @var Properties\Properties\PropertyFactory
     */
    protected $propertyFactory;

    /**
     * Maps to id in properties_aggregate
     * @var int
     */
    protected $id;

    /**
     * Maps to name in properties_aggregate
     * @var string
     */
    protected $name;

    /**
     * Maps to pk in properties_aggregate
     * @var string
     */
    protected $pk;

    /**
     * Maps to pk_value in properties_aggregate
     * @var int
     */
    protected $pkValue;

    /**
     * Maps to cached_value in properties_aggregate
     * Any saved values will be saved as a json in this field for caching purpose
     * @var string json encoded values
     */
    protected $cachedProperties;

    /**
     * @var Array popuplatd properties from $cachedProperties
     */
    protected $properties;

    /**
     * set, update, delete operations are transactional.
     * Unless you call save() explicitly, those methods do not have any impact on the data.
     * $pendingProperties holds any pending Property objects that's waiting to be saved/updated/deleted.
     * @var Array any pending properties for delete/insert/update operations
     */
    protected $pendingProperties = [];

    public function __construct(
        QueryBuilderInterface $queryBuilder,
        EntityInterface $entity,
        TableGatewayFactory $tableGatewayFactory,
        PropertyFactory $propertyFactory
    )
    {
        $this->queryBuilder        = $queryBuilder;
        $this->entity              = $entity;
        $this->tableGatewayFactory = $tableGatewayFactory;
        $this->propertyFactory     = $propertyFactory;

        $this->loadOrCreate();
    }

    /**
     * Creates a new row in $tableName
     * or loads an existing row
     * @return Void
     */
    protected function loadOrCreate()
    {
        $index = $this->queryBuilder->selectFirst(
            $this->tableName,
            [
                'name'     => $this->entity->getName(),
                'pk'       => $this->entity->getIdentifierName(),
                'pk_value' => $this->entity->getIdentifier()
            ]
        );

        if (!$index) {
            $index = $this->createNewRecord();
        }

        $this->id               = (int) $index->id;
        $this->name             = $index->name;
        $this->pk               = $index->pk;
        $this->pkValue          = $index->pk_value;
        $this->cachedProperties = json_decode($index->cached_properties);

        $this->populateCachedProperties();

        $this->loaded = true;
    }

    /**
     * Decode cached json and generate Value objects from it
     * @return Void
     */
    protected function populateCachedProperties()
    {
        if (!$this->cachedProperties) {
            $this->properties = [];

            return;
        }

        foreach ($this->cachedProperties as $key=>$property) {
            $this->properties[$property->name] = $this->propertyFactory->createWithValues($property);
        }
    }

    /**
     * Create a new record for the aggregate index
     * @return [type] [description]
     */
    protected function createNewRecord()
    {
        $index = $this->queryBuilder->insert(
            $this->tableName,
            [
                'name'     => $this->entity->getName(),
                'pk'       => $this->entity->getIdentifierName(),
                'pk_value' => $this->entity->getIdentifier()
            ]
        );

        $index = $this->queryBuilder->selectFirst($this->tableName, ['id'=>$index]);

        return $index;
    }

    /**
     * Add a temporary Property object to the $pendingProperties
     * @return Void
     */
    protected function addPendingProperty($name, $value, $operation, $id = null)
    {
        $values           = new stdClass;
        $values->name     = $name;
        $values->value    = $value;
        $values->id       = $id;
        $values->index_id = $this->id;

        $property                       = $this->propertyFactory->createWithValues($values);
        $this->pendingProperties[$name] = [$operation, $property];
    }

    /**
     * Updates an exsiting property by its key
     * @return Void
     */
    protected function update($key, $value, $operation, $id = null)
    {
        if (!$this->has($key, $value)) {
            throw new KeyNotFoundException($key . ' doesn\'t exist. Please use set($key, $value, $type) to create it');
        }

        $this->addPendingProperty($key, $value, $operation, $id);

        return $this;
    }

    /**
     * Sets a new Property or updates an existing Property
     * @return Aggregate
     */
    public function set($key, $value)
    {
        if ($this->has($key)) {
            $this->update($key, $value, 'update', $this->get($key, true)->getId());
        } else {
            $this->addPendingProperty($key, $value, 'create');
        }

        return $this;
    }

    /**
     * Deletes a Property
     * @return Aggregate
     */
    public function delete($key)
    {
        if (!$this->has($key)) {
            throw new KeyNotFoundException($key . ' doesn\'t exist. You can\'t unset it');
        }

        $property                      = $this->get($key, true);
        $this->pendingProperties[$key] = ['delete', $property];

        return $this;
    }

    /**
     * See if a key exists
     * @return Boolean
     */
    public function has($key)
    {
        if (!$this->loaded) {
            $this->loadOrCreate();
        }

        return array_key_exists($key, $this->properties);
    }

    /**
     * Returns a Property by its key
     * @param  string         $key
     * @param  boolean        $returnObject
     * @return Property|mixed returns Property if set to true
     */
    public function get($key, $returnObject = false)
    {
        if (!$this->has($key)) {
            throw new KeyNotFoundException($key . ' doesn\'t exist. Please use set($key, $value, $type) to create it');
        }

        if ($returnObject) {
            return $this->properties[$key];
        }

        return $this->properties[$key]->getValue();
    }

    /**
     * Destroy all the properties and and the aggregate
     * @return Boolean
     */
    public function destroy()
    {
         try {

            $this->queryBuilder->beginTransaction();

            foreach ($this->supportedDataTypes as $supportedDataType) {
                $tableGateway = $this->tableGatewayFactory->create($this->queryBuilder, $supportedDataType);
                $tableGateway->deleteByIndexId($this->id);
            }

            $this->queryBuilder->delete($this->tableName, ['id'=>$this->id]);
            $this->queryBuilder->commit();

        } catch (\Exception $e) {
            $this->queryBuilder->rollback();
            throw $e;
        }

        $this->id                = null;
        $this->name              = null;
        $this->pk                = null;
        $this->pkValue           = null;
        $this->cachedProperties  = null;
        $this->pendingProperties = [];
        $this->loaded            = false;
    }

    /**
     * Insert/Update any existing pending Value objects
     * @return Boolean
     */
    public function save()
    {
        try {

            $this->queryBuilder->beginTransaction();

            foreach ($this->pendingProperties as $pendingProperty) {

                $operation    = $pendingProperty[0];
                $property     = $pendingProperty[1];
                $tableGateway = $this->tableGatewayFactory->create($this->queryBuilder, $property->getDataType());

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
            $this->sync();
        } catch (\Exception $e) {
            $this->queryBuilder->rollback();
        }

        $this->pendingProperties = [];

        return true;
    }

    /**
     * Resync a cached_properties of properties_aggregate
     */
    public function sync()
    {
        $index = new stdClass;

        $queries = [];
        foreach ($this->supportedDataTypes as $type) {
            $fields = "{$type}.id, {$type}.index_id, {$type}.name, {$type}.value, '{$type}' as type";
            $queries[] = "select {$fields} from properties_{$type} as `{$type}` where index_id={$this->id}";
        }

        $queryString = implode(' union ', $queries);

        $properties = $this->queryBuilder->query($queryString);

        if (count($properties) === 0) {
            $index = null;
        } else {
            foreach ($properties as $property) {
                $index->{$property->name} = $property;
            }
        }

        $this->cachedProperties = $index;
        $this->populateCachedProperties();

        $this->queryBuilder->update(
            $this->tableName,
            ['cached_properties'=>json_encode($index)],
            $this->id
        );
    }

    public function all()
    {
        return $this->properties;
    }

    public function getPendingProperties()
    {
        return $this->pendingProperties;
    }

    /**
     * Returns a list of keys
     * @return Array
     */
    public function keys()
    {
        return array_keys($this->properties);
    }

    public function getIndexId()
    {
        return $this->id;
    }

    public function count()
    {
        return count($this->properties);
    }
}
