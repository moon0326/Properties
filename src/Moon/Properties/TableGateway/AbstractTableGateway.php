<?php namespace Moon\Properties\TableGateway;

use Moon\Properties\QueryBuilderInterface;
use Moon\Properties\Property;

abstract class AbstractTableGateway implements TableGatewayInterface
{
    protected $queryBuilder;

    abstract protected function getTableName();

    public function __construct(QueryBuilderInterface $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    public function create(Property $property)
    {
        $values = [
            'index_id' => $property->index_id,
            'key'      => $property->key,
            'value'    => $property->value
        ];

        if (isset($value->type)) {
            $values['type'] = $property->type;
        }

        $result = $this->queryBuilder->insert($this->getTableName(), $values);

        return $result;
    }

    public function createOrUpdate(Property $property)
    {
        if ($property->id) {
            return $this->update($property);
        }

        return $this->create($property);
    }

    public function update(Property $property)
    {
        $result = $this->queryBuilder->update($this->getTableName(),[
            'key'      => $property->key,
            'value'    => $property->value
        ], $property->id);

        return $result;
    }

    public function delete(Property $property)
    {
        $result = $this->queryBuilder->delete($this->getTableName(), $property->id);
        return $result;
    }

    public function findByIndexId($indexId)
    {
        $result = $this->queryBuilder->select($this->getTableName(), ['index_id'=>$indexId]);

        if (!is_array($result)) {
            return [];
        }

        return $result;
    }
}
