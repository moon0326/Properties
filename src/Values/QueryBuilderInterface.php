<?php namespace Values;

interface QueryBuilderInterface
{
	public function select($table, $wheres);
	public function findOne($table, $id);
	// public function insert($table, $values);
	public function update($table, $values, $id);
	// public function beginTransaction();
	// public function rollback();
	// public function commit();
}