<?php namespace Moon\Properties\Frameworks\Kohana;

use Moon\Properties\QueryBuilderInterface;
use DB;
use Database;

class QueryBuilder implements QueryBuilderInterface
{
    public function select($table, array $wheres = [])
    {
        $builder = DB::select()->from($table);
        foreach ($wheres as $key=>$value) {
            $builder->where($key, '=', $value);
        }

        $record = $builder->as_object()->execute();
        $count = count($record);

        if ($count === 0) {
            return null;
        }

        if ($count === 1) {
            return [$record->current()];
        }

        return $record;
    }

    public function insert($table, array $values)
    {
        $record = DB::insert($table, array_keys($values))->values($values)->execute();
        return $record[0];
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
        $record = DB::update($table)->set($values)->where('id','=',$id)->execute();
        return $record;
    }

    public function delete($table, $id)
    {
        $record = DB::table($table)->where('id',$id)->delete();
        return $record;
    }

    public function beginTransaction()
    {
        Database::instance()->begin();
    }

    public function rollback()
    {
        Database::instance()->rollback();
    }

    public function commit()
    {
        Database::instance()->commit();
    }

}