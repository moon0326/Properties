<?php namespace Properties\tests;

use PHPUnit_Framework_TestCase;
use \Moon\Properties\Aggregate;
use \Moon\Properties\Property;

use \Mockery as m;

class AggregateTest extends PHPUnit_Framework_TestCase
{
	protected $aggregate;

    public function tearDown()
    {
        m::close();
    }

	public function testGet()
	{
		$aggregate = $this->getAggregateMock();
		$name = $aggregate->get('name');
		$this->assertEquals($name, 'moon');
	}

	public function testKeys()
	{
		$aggregate = $this->getAggregateMock();
		$keys = $aggregate->keys();
		$this->assertEquals(1, count($keys));
		$this->assertEquals('name', $keys[0]);
	}

	public function testAll()
	{
		$aggregate = $this->getAggregateMock();
		$allValues = $aggregate->all();
		$this->assertEquals(1, count($allValues));
		$this->assertTrue($allValues['name'] instanceOf Property);
		$this->assertEquals($allValues['name']->value, 'moon');
	}

	public function testHas()
	{
		$aggregate = $this->getAggregateMock();
		$this->assertFalse($aggregate->has('age'));
		$this->assertTrue($aggregate->has('name'));
	}

	/**
	 * Mock Objects
	 */
	protected function getAggregateMock($queryBuilderMock = null, $entityMock = null, $tableGatewayMock = null)
	{

		if (!$queryBuilderMock) {
			$_queryBuilderMock = $this->getQueryBuilderInterfaceMock();
			$_queryBuilderMock['methods']['selectFirst']->andReturn($this->getAggregateRecordStub());
			$queryBuilderMock = $_queryBuilderMock['mock'];
		}

		if (!$entityMock) {
			$entityMock = $this->getEntityInterfaceMock()['mock'];
		}

		if (!$tableGatewayMock) {
			$tableGatewayMock = $this->getTableGatewayFactoryInterfaceMock()['mock'];
		}

		return new Aggregate($queryBuilderMock, $entityMock, $tableGatewayMock);
	}

	protected function getEntityInterfaceMock()
	{
		$entityInterface = m::mock('\Moon\Properties\EntityInterface');
		$getName = $entityInterface->shouldReceive('getName');
		$getIdentifier = $entityInterface->shouldReceive('getIdentifier');
		$getIdentifierName = $entityInterface->shouldReceive('getIdentifierName');

		return [
			'mock' => $entityInterface,
			'methods' => compact('getName', 'getIdentifier', 'getIdentifierName')
		];
	}

	protected function getQueryBuilderInterfaceMock()
	{
		$queryBuilderInterface = m::mock('\Moon\Properties\QueryBuilderInterface');
		$select = $queryBuilderInterface->shouldReceive('select');
		$selectFirst = $queryBuilderInterface->shouldReceive('selectFirst');
		$insert = $queryBuilderInterface->shouldReceive('insert');
		$update = $queryBuilderInterface->shouldReceive('update');
		$beginTransaction = $queryBuilderInterface->shouldReceive('beginTransaction');
		$rollback = $queryBuilderInterface->shouldReceive('rollback');
		$commit = $queryBuilderInterface->shouldReceive('commit');

		return [
			'mock' => $queryBuilderInterface,
			'methods' => compact('select', 'selectFirst', 'insert', 'update', 'beginTransaction', 'rollback', 'commit')
		];
	}

	protected function getTableGatewayFactoryInterfaceMock()
	{
		$tableGatewayFactoryInterface = m::mock('\Moon\Properties\TableGateway\TableGatewayFactoryInterface');
		$create = $tableGatewayFactoryInterface->shouldReceive('create');

		return [
			'mock' => $tableGatewayFactoryInterface,
			'methods' => compact('create')
		];
	}

	/**
	 * Stub
	 */
	protected function getAggregateRecordStub($id = 1, $name = 'stub', $pk = 'id', $pk_value = 1)
	{

		$indexStub = new \stdClass;
		$indexStub->id = $id;
		$indexStub->name = $name;
		$indexStub->pk = $pk;
		$indexStub->pk_value = $pk_value;

		$properties = new \stdClass;

		$stubPropertyValues = new \stdClass;
		$stubPropertyValues->id = 1;
		$stubPropertyValues->index_id = 1;
		$stubPropertyValues->key = 'name';
		$stubPropertyValues->value = 'moon';
		$properties->name = new Property($stubPropertyValues) ;
		$indexStub->cached_value = json_encode($properties);
		return $indexStub;
	}

}