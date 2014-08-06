<?php namespace Moon\Properties\TableGateways;

use Moon\Properties\QueryBuilderInterface;
use Moon\Properties\Properties\Property;

abstract class TableGateway implements TableGatewayInterface
{
    protected $queryBuilder;

    abstract protected function getTableName();

    public function __construct(QueryBuilderInterface $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    public function create(Property $property)
    {
        return $this->queryBuilder->insert($this->getTableName(), [
            'index_id' => $property->getIndexId(),
            'name'     => $property->getName(),
            'value'    => $property->getValue()
        ]);
    }

    public function createOrUpdate(Property $property)
    {
        if ($property->getId()) {
            return $this->update($property);
        }

        return $this->create($property);
    }

    public function update(Property $property)
    {
        return $this->queryBuilder->update($this->getTableName(), [
            'name'  => $property->getName(),
            'value' => $property->getValue()
        ], $property->getId());
    }

    public function delete(Property $property)
    {
        return $this->queryBuilder->delete($this->getTableName(), ['id'=>$property->getId()]);
    }

    public function findByIndexId($indexId)
    {
        $result = $this->queryBuilder->select($this->getTableName(), ['index_id'=>$indexId]);

        if (!is_array($result)) {
            return [];
        }

        return $result;
    }

    public function deleteByIndexId($indexId)
    {
        return $this->queryBuilder->delete($this->getTableName(), ['index_id'=>$indexId]);
    }
}
