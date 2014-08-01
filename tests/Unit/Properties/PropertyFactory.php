<?php

use Moon\Properties\Properties\PropertyFactory;
use Moon\Properties\Properties\DecimalProperty;
use Moon\Properties\Properties\IntegerProperty;
use Moon\Properties\Properties\PhpProperty;
use Moon\Properties\Properties\TextProperty;
use Moon\Properties\Properties\VarcharProperty;

use \stdClass;

class PropertyTest extends \PHPUnit_Framework_TestCase
{
	protected $factory;

	public function testDecimalProperty()
	{
		$value = $this->getProperty(13.00);
		$decimalProperty = $this->factory->createWithValues($value);
		$this->assertTrue($decimalProperty instanceOf DecimalProperty);

		$value = $this->getProperty(13.11);
		$decimalProperty = $this->factory->createWithValues($value);
		$this->assertTrue($decimalProperty instanceOf DecimalProperty);
	}

	public function testDecimalPropertyWithMoreThanTwoPrecisions()
	{
		$this->setExpectedException("Moon\Properties\Exceptions\UnknownValueTypeException");
		$value = $this->getProperty(13.113);
		$decimalProperty = $this->factory->createWithValues($value);
	}

	public function testIntegerProperty()
	{
		$value = $this->getProperty(1);
		$integerProperty = $this->factory->createWithValues($value);
		$this->assertTrue($integerProperty instanceOf IntegerProperty);
	}

	public function testPhpProperty()
	{
		$obj = new stdClass;
		$value = $this->getProperty($obj);
		$phpProperty = $this->factory->createWithValues($value);
		$this->assertTrue($phpProperty instanceOf PhpProperty);
	}

	public function testTextProperty()
	{
		$value = $this->getProperty(str_repeat('a', 256));
		$textProperty = $this->factory->createWithValues($value);
		$this->assertTrue($textProperty instanceOf TextProperty);
	}

	public function testVarcharProperty()
	{
		$value = $this->getProperty('this is varchar');
		$varcharProperty = $this->factory->createWithValues($value);
		$this->assertTrue($varcharProperty instanceOf VarcharProperty);
	}


	/**
	 * Helper
	 */
	protected function getProperty($value)
	{
		$obj = new stdClass;
		$obj->index_id = 1;
		$obj->name = 'test';
		$obj->value = $value;
		return $obj;
	}

	/**
	 * Setup
	 */
	public function setUp()
	{
		$this->factory = new PropertyFactory();
	}

	public function tearDown()
	{
		$this->factory = null;
	}
}