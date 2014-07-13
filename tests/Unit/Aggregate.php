<?php namespace Properties\tests\Unit;

use PHPUnit_Framework_TestCase;
use \Moon\Properties\Aggregate;
use \Moon\Properties\Property;
use \Mockery as m;

class AggregateTest extends PHPUnit_Framework_TestCase
{
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

    public function testGettingInvalidKey()
    {
        $this->setExpectedException("\Moon\Properties\Exceptions\KeyNotFoundException");
        $aggregate = $this->getAggregateMock();
        $aggregate->get("i am not here");
    }

    public function testCreatingTableGateway()
    {
        $values = [
            'name'   => ['moon', 'Varchar'],
            'age'    => [999, 'Integer'],
            'amount' => [13.2, 'Double'],
            'amount2' => [13.00, 'Double'],
        ];

        $aggregate = $this->getAggregateMock();

        foreach ($values as $key=>$value) {
            $aggregate->set($key, $value[0]);
        }

        foreach ($aggregate->getPendingProperties() as $key=>$pendingProperty) {
            $property = $pendingProperty[1];
            $this->assertEquals($property->type, $values[$key][1]);
        }
    }

    public function testDoubleWithMoreThanTwoPrecisions()
    {
        $this->setExpectedException('\Moon\Properties\Exceptions\UnknownValueTypeException');
        $aggregate = $this->getAggregateMock();
        $aggregate->set('test', 13.333);
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

    public function testSet()
    {
        $fakePropertyValues           = new \stdClass;
        $fakePropertyValues->id       = 1;
        $fakePropertyValues->index_id = 1;
        $fakePropertyValues->key      = 'dummy';
        $fakePropertyValues->value    = 'is dummy';

        $tableGateway = $this->getTableGatewayMock();
        $tableGateway['methods']['findByIndexId']->andReturn([$fakePropertyValues]);

        $tableGatewayMock = $this->getTableGatewayFactoryInterfaceMock();
        $tableGatewayMock['methods']['create']->andReturn($tableGateway['mock']);

        $aggregate = $this->getAggregateMock(null, null, $tableGatewayMock['mock']);
        $aggregate->set($fakePropertyValues->key, $fakePropertyValues->value);
        $aggregate->save();

        $dummy = $aggregate->get('dummy');

        $this->assertEquals($dummy, $fakePropertyValues->value);
    }

    public function testGetPendingProperties()
    {
        $aggregate = $this->getAggregateMock();
        $aggregate->set("i'm","the bat man!");
        $aggregate->set("he is","the super man!");

        $pendingProperties = $aggregate->getPendingProperties();

        $im   = $pendingProperties["i'm"][1];
        $heis = $pendingProperties["he is"][1];

        $this->assertEquals(count($pendingProperties), 2);
        $this->assertTrue($im instanceof Property);
        $this->assertTrue($heis instanceof Property);
        $this->assertEquals($im->value, 'the bat man!');
        $this->assertEquals($heis->value, 'the super man!');
    }

    public function testDelete()
    {
        $fakePropertyValues = new \stdClass;
        $fakePropertyValues->id = 1;
        $fakePropertyValues->index_id = 1;
        $fakePropertyValues->key = 'dummy';
        $fakePropertyValues->value = 'is dummy';

        $tableGateway = $this->getTableGatewayMock();
        $tableGateway['methods']['findByIndexId']->with(1)->andReturn([$fakePropertyValues], []);

        $tableGatewayMock = $this->getTableGatewayFactoryInterfaceMock();
        $tableGatewayMock['methods']['create']->andReturn($tableGateway['mock']);

        $aggregate = $this->getAggregateMock(null, null, $tableGatewayMock['mock']);
        $aggregate->set($fakePropertyValues->key, $fakePropertyValues->value);
        $aggregate->save();

        $dummy = $aggregate->get('dummy');

        $this->assertEquals($dummy, $fakePropertyValues->value);

        $aggregate->delete('dummy');
        $aggregate->save();

        $this->assertEquals(0, count($aggregate->all()));
    }

    /**
     * Mock Objects
     */
    protected function getAggregateMock($queryBuilderMock = null, $entityMock = null, $tableGatewayMock = null)
    {

        if (!$queryBuilderMock) {
            $_queryBuilderMock = $this->getQueryBuilderInterfaceMock();
            $_queryBuilderMock['methods']['selectFirst']->andReturn($this->getFakeAggregateRecord());
            $queryBuilderMock  = $_queryBuilderMock['mock'];
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
        $entityInterface   = m::mock('\Moon\Properties\EntityInterface');
        $getName           = $entityInterface->shouldReceive('getName');
        $getIdentifier     = $entityInterface->shouldReceive('getIdentifier');
        $getIdentifierName = $entityInterface->shouldReceive('getIdentifierName');

        return [
            'mock'    => $entityInterface,
            'methods' => compact('getName', 'getIdentifier', 'getIdentifierName')
        ];
    }

    protected function getQueryBuilderInterfaceMock()
    {
        $queryBuilderInterface = m::mock('\Moon\Properties\QueryBuilderInterface');
        $select                = $queryBuilderInterface->shouldReceive('select');
        $selectFirst           = $queryBuilderInterface->shouldReceive('selectFirst');
        $insert                = $queryBuilderInterface->shouldReceive('insert');
        $update                = $queryBuilderInterface->shouldReceive('update');
        $beginTransaction      = $queryBuilderInterface->shouldReceive('beginTransaction');
        $rollback              = $queryBuilderInterface->shouldReceive('rollback');
        $commit                = $queryBuilderInterface->shouldReceive('commit');

        return [
            'mock'    => $queryBuilderInterface,
            'methods' => compact('select', 'selectFirst', 'insert', 'update', 'beginTransaction', 'rollback', 'commit')
        ];
    }

    protected function getTableGatewayFactoryInterfaceMock()
    {
        $tableGatewayFactoryInterface = m::mock('\Moon\Properties\TableGateway\TableGatewayFactoryInterface');
        $create                       = $tableGatewayFactoryInterface->shouldReceive('create');

        return [
            'mock'    => $tableGatewayFactoryInterface,
            'methods' => compact('create')
        ];
    }

    protected function getTableGatewayMock()
    {
        $mock           = m::mock('\Moon\Properties\TableGateway\TableGatewayInterface');
        $createOrUpdate = $mock->shouldReceive('createOrUpdate');
        $findByIndexId  = $mock->shouldReceive('findByIndexId');
        $delete         = $mock->shouldReceive('delete');

        return [
            'mock'    => $mock,
            'methods' => compact('createOrUpdate', 'findByIndexId', 'delete')
        ];
    }

    protected function getFakeAggregateRecord($id = 1, $name = 'stub', $pk = 'id', $pk_value = 1)
    {
        $indexStub = new \stdClass;
        $indexStub->id = $id;
        $indexStub->name = $name;
        $indexStub->pk = $pk;
        $indexStub->pk_value = $pk_value;

        $properties = new \stdClass;
        $stubPropertyValues           = new \stdClass;
        $stubPropertyValues->id       = 1;
        $stubPropertyValues->index_id = 1;
        $stubPropertyValues->key      = 'name';
        $stubPropertyValues->value    = 'moon';

        $properties->name             = new Property($stubPropertyValues) ;
        $indexStub->cached_value      = json_encode($properties);

        return $indexStub;
    }
}