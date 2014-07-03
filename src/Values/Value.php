<?php namespace Values;

class Value
{
	public $id;
	public $indexId;
	public $key;
	public $value;

	public function __construct($values)
	{
		$this->id = $values->id;
		$this->indexId = $values->index_id;
		$this->key = $values->key;
		$this->value = $values->value;
	}
}