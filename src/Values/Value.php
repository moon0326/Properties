<?php namespace Values;

class Value
{
	public $id;
	public $parentId;
	public $type;
	public $value;

	public function __construct($values)
	{
		foreach ($values as $key=>$value) {
			if (isset($this->$key)) {
				$this->key = $value;
			}
		}
	}
}