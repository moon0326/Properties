<?php namespace Properties;

interface QueryBuilderInterface
{
	public function select($table, array $wheres);
	public function selectFirst($table, array $wheres);
	public function insert($table, array $values);
	public function update($table, array $values, $id);
	public function beginTransaction();
	public function rollback();
	public function commit();
}