<?php namespace Values\Facades\Kohana;

use Values\QueryBuilderInterface;
use \DB as DB;

class QueryBuilder implements QueryBuilderInterface
{
	public function select($table, array $wheres = [])
	{
		$builder = DB::select()->from($table);
		foreach ($wheres as $key=>$value) {
			$builder->where($key, '=', $value);
		}

		$record = $builder->execute();
		if (count($record) === 0) {
			return null;
		}

		return $record;
	}

	public function insert($table, array $values)
	{
		$record = DB::insert($table, array_keys($values))->values($values);
		return $record;
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
		$record = DB::table($table)->where('id',$id)->update($values);
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