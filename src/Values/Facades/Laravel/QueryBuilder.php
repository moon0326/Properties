<?php namespace Values\Facades\Laravel;

use Values\QueryBuilderInterface;

use \DB as DB;

class QueryBuilder implements QueryBuilderInterface
{
	public function select($table, $wheres = [])
	{
		$builder = \DB::table($table);
		foreach ($wheres as $key=>$value) {
			$builder->where($key, '=', $value);
		}

		$record = $builder->get();
		if (count($record) === 0) {
			return null;
		}

		return $record;
	}

	public function insert($table, $values)
	{
		$record = \DB::table($table)->insertGetId($values);
		return $record;
	}

	public function findOne($table, $wheres)
	{
		$record = $this->select($table, $wheres);
		if (count($record)) {
			return $record[0];
		}

		return $record;
	}

	public function update($table, $values, $id)
	{
		$record = \DB::table($table)->where('id',$id)->update($values);
		return $record;
	}

	public function beginTransaction()
	{
		DB::beginTransaction();
	}

	public function rollback()
	{
		DB::rollback();
	}

	public function commit()
	{
		DB::commit();
	}

}