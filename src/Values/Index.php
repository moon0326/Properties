<?php namespace Values;

class Index implements IndexInterface
{
	public $id;
	public $tableName;
	public $tablePrimaryKeyName;
	public $tablePrimaryValue;
	public $value;

	public function __construct($values)
	{

		$this->id = $values['id'];
		$this->tableName = $values['table_name'];
		$this->tablePrimaryKeyName = $values['table_pk_name'];
		$this->tablePrimaryValue = $values['table_pk_value'];
		$this->value = $values['value'];
	}

	public function getId()
	{
		return $this->id;
	}
}