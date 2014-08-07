<?php namespace Moon\Properties\Frameworks\Native;

use Moon\Properties\QueryBuilderInterface;

use \PDO;

class QueryBuilder implements QueryBuilderInterface
{
    protected $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    protected function createWheres(array $wheres = [], $implodeSeparator = ' and ')
    {
        $where = [];

        foreach ($wheres as $key=>$value) {
            $where[] = '`'.$key.'`=?';
        }

        return implode($implodeSeparator,$where);
    }

    public function select($table, array $wheres = [])
    {
        $queryStr = 'select * from :table where ' . $this->createWheres($wheres);
        $sql = $this->conn->prepare($queryStr);
        $sql->bindValue(':table', $table, PDO::PARAM_STR);

        $execute = $sql->execute(array_values($wheres));

        if (!$execute) {
            return null;
        }

        $rows = $sql->fetchAll(PDO::FETCH_CLASS, 'stdClass');

        if (count($rows)) {
            return $rows;
        }

        return null;
    }

    public function insert($table, array $values)
    {
        $cols = array_map(function($value) {
            return '`'.$value.'`';
        }, array_keys($values));

        $colsStr = '(' . implode(',', $cols) . ')';
        $valuesPlaceholder = [];

        foreach ($cols as $col) {
            $valuesPlaceholder[] = '?';
        }

        $valuesPlaceholder = '(' . implode(',',$valuesPlaceholder) . ')';

        $query = $this->conn->prepare("insert into ". $table . ' ' . $colsStr . ' values '.$valuesPlaceholder);

        $query->execute(array_values($values));

        $id = $this->conn->lastInsertId();

        return $id;
    }

    public function selectFirst($table, array $wheres)
    {
        $record = $this->select($table, $wheres);

        if ($record) {
            return $record[0];
        }

        return $record;
    }

    public function update($table, array $values, $id)
    {
        $queryStr = 'update :table set ' . $this->createWheres($values,',') . ' where id = ' . $id;
        $query = $this->conn->prepare($queryStr);
        $query->bindValue(':table', $table, PDO::PARAM_STR);

        return $query->execute(array_values($values));
    }

    public function delete($table, array $values)
    {
        $queryStr = 'delete from :table where ' . $this->createWheres($values,',');
        $query = $this->conn->prepare($queryStr);
        $query->bindValue(':table', $table, PDO::PARAM_STR);

        return $query->execute(array_values($values));
    }

    public function beginTransaction()
    {
        $this->conn->beginTransaction();
    }

    public function rollback()
    {
        $this->conn->rollback();
    }

    public function commit()
    {
        $this->conn->commit();
    }
}