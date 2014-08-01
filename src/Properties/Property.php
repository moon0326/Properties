<?php namespace Moon\Properties\Properties;

abstract class Property
{
	private $id;
	private $index_id;
	private $name;
	private $value;

	abstract protected function decorateValue($mixed);
	abstract public function getDataType();

	public function __construct($values)
	{
		if (isset($values->id)) {
			$this->id       = $values->id;
		}

		$this->index_id = $values->index_id;
		$this->name     = $values->name;
		$this->value    = $this->decorateValue($values->value);
	}

	public function getId()
	{
		return $this->id;
	}

	public function getIndexId()
	{
		return $this->index_id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getValue()
	{
		return $this->value;
	}
}