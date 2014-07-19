<?php namespace Properties\tests\Unit;

use \Moon\Properties\Property;
use \Mockery as m;

class PropertyTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {

    }

    public function testSettingAndGettingInteger()
    {
    	$obj = new stdClass;
    	$obj->id = 1;
    	$obj->index_id = 1;
    	$obj->key = 'test';
    	$obj->value = 33;
    	$obj->type = 'Integer';

    	$property = new Property($obj);

    }

}