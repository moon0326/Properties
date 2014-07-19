<?php namespace Moon\Properties\TableGateway;

use Moon\Properties\QueryBuilderInterface;
use Moon\Properties\Property;
use Moon\Properties\Properties\PropertyInterface;

abstract class AbstractTableGateway implements TableGatewayInterface
{
    protected $queryBuilder;

    abstract protected function getTableName();

    public function __construct(QueryBuilderInterface $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    public function create(PropertyInterface $property)
    {
        $values = [
            'index_id' => $property->getIndexId(),
            'name'     => $property->getName(),
            'value'    => $property->getValue()
        ];

        $result = $this->queryBuilder->insert($this->getTableName(), $values);

        return $result;
    }

    public function createOrUpdate(PropertyInterface $property)
    {
        if ($property->getId()) {
            return $this->update($property);
        }

        return $this->create($property);
    }

    public function update(PropertyInterface $property)
    {
        $result = $this->queryBuilder->update($this->getTableName(),[
            'name'      => $property->getName(),
            'value'    => $property->getValue()
        ], $property->getId());

        return $result;
    }

    public function delete(PropertyInterface $property)
    {
        $result = $this->queryBuilder->delete($this->getTableName(), ['id'=>$property->getId()]);
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

    public function deleteByIndexId($indexId)
    {
        return $this->queryBuilder->delete($this->getTableName(), ['index_id'=>$indexId]);
    }
}
