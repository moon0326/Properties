<?php namespace Values\Facades\Laravel;

use Values\QueryBuilderInterface;

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
}