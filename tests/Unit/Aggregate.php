<?php

use \Moon\Properties\Aggregate;
use \Moon\Properties\Properties\PropertyFactory;
use \Mockery as m;

use \Moon\Properties\Properties\VarcharProperty;
use \stdClass;

class AggregateTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * @test
     */
    public function it_should_return_moon()
    {
        $aggregate = $this->getAggregateMock();
        $name = $aggregate->get('name');
        $this->assertEquals($name, 'moon');
    }

    /**
     * @test
     */
    public function it_should_throw_KeyNotFoundException_when_retrieving_unset_key()
    {
        $this->setExpectedException("\Moon\Properties\Exceptions\KeyNotFoundException");
        $aggregate = $this->getAggregateMock();
        $aggregate->get("i am not here");
    }

    /**
     * @test
     * After setting a value, the value becomes a pending property until we call save() method
     * Check to see if those pending properties have a correct Propety object
     * Expected Objects
     * | Type    | Object          |
     * | Varchar | VarcharProperty |
     * | Integer | IntegerProperty |
     * | Decimal | DecimalProperty |
     */
    public function it_should_return_correct_Property_object_for_each_data_types()
    {
        $values = [
            'name'    => ['moon', 'Moon\Properties\Properties\VarcharProperty'],
            'age'     => [999, 'Moon\Properties\Properties\IntegerProperty'],
            'amount'  => [13.2, 'Moon\Properties\Properties\DecimalProperty'],
            'amount2' => [13.00, 'Moon\Properties\Properties\DecimalProperty'],
        ];

        $aggregate = $this->getAggregateMock();

        foreach ($values as $key=>$value) {
            $aggregate->set($key, $value[0]);
        }

        foreach ($aggregate->getPendingProperties() as $key=>$pendingProperty) {
            $property = $pendingProperty[1];
            $this->assertTrue(get_class($property) === $values[$key][1]);
        }
    }

    /**
     * @test
     * We don't support numbers with more than two previsions
     * Make sure we throw an exception
     */
    public function it_should_throw_UnknownValueTypeException_with_more_than_two_precisions()
    {
        $this->setExpectedException('\Moon\Properties\Exceptions\UnknownValueTypeException');
        $aggregate = $this->getAggregateMock();
        $aggregate->set('test', 13.333);
    }

    /**
     * @test
     */
    public function it_should_return_array_of_keys_when_calling_keys_method()
    {
        $aggregate = $this->getAggregateMock();
        $keys = $aggregate->keys();
        $this->assertEquals(1, count($keys));
        $this->assertEquals('name', $keys[0]);
    }

    /**
     * @test
     */
    public function it_should_return_all_properties()
    {
        $aggregate = $this->getAggregateMock();
        $allValues = $aggregate->all();

        $this->assertEquals(1, count($allValues));
        $this->assertTrue($allValues['name'] instanceOf VarcharProperty);
        $this->assertEquals($allValues['name']->getValue(), 'moon');
    }

    /**
     * @test
     */
    public function it_should_return_true_with_exisiting_value()
    {
        $aggregate = $this->getAggregateMock();
        $this->assertFalse($aggregate->has('age'));
        $this->assertTrue($aggregate->has('name'));
    }

    public function it_should_set_value_on_Aggregate()
    {
        $fakePropertyValues           = new stdClass;
        $fakePropertyValues->id       = 1;
        $fakePropertyValues->index_id = 1;
        $fakePropertyValues->name     = 'dummy';
        $fakePropertyValues->value    = 'is dummy';
        $fakePropertyValues->type     = 'Varchar';

        $queryBuilder = $this->getQueryBuilderInterfaceMock();
        $queryBuilder['methods']['query']->andReturn([$fakePropertyValues]);
        $queryBuilder['methods']['selectFirst']->andReturn($this->getFakeAggregateRecord());

        $tableGateway = $this->getTableGatewayMock();
        $tableGateway['methods']['findByIndexId']->andReturn([$fakePropertyValues]);

        $tableGatewayMock = $this->getTableGatewayFactoryMock();
        $tableGatewayMock['methods']['create']->andReturn($tableGateway['mock']);

        $aggregate = $this->getAggregateMock($queryBuilder['mock'], null, $tableGatewayMock['mock']);
        $aggregate->set($fakePropertyValues->name, $fakePropertyValues->value);
        $aggregate->save();

        $dummy = $aggregate->get('dummy');

        $this->assertEquals($dummy, $fakePropertyValues->value);
    }

    /**
     * @test
     */
    public function it_should_return_an_instance_of_Property()
    {
        $aggregate = $this->getAggregateMock();
        $aggregate->set("i'm","the bat man!");
        $aggregate->set("he is","the super man!");

        $pendingProperties = $aggregate->getPendingProperties();

        $im   = $pendingProperties["i'm"][1];
        $heis = $pendingProperties["he is"][1];

        $this->assertEquals(count($pendingProperties), 2);
        $this->assertTrue($im instanceof VarcharProperty);
        $this->assertTrue($heis instanceof VarcharProperty);
        $this->assertEquals($im->getValue(), 'the bat man!');
        $this->assertEquals($heis->getValue(), 'the super man!');
        $this->assertFalse($aggregate->has("i'm"));
        $this->assertFalse($aggregate->has("he is"));
    }

    /**
     * @test
     */
    public function it_should_remove_property_from_Aggregate()
    {
        $fakePropertyValues = new stdClass;
        $fakePropertyValues->id = 1;
        $fakePropertyValues->index_id = 1;
        $fakePropertyValues->name = 'dummy';
        $fakePropertyValues->value = 'is dummy';
        $fakePropertyValues->type  = 'Varchar';

        $tableGateway = $this->getTableGatewayMock();

        $queryBuilder = $this->getQueryBuilderInterfaceMock();
        $queryBuilder['methods']['query']->andReturn([$fakePropertyValues], []);
        $queryBuilder['methods']['selectFirst']->andReturn($this->getFakeAggregateRecord());

        $tableGatewayMock = $this->getTableGatewayFactoryMock();
        $tableGatewayMock['methods']['create']->andReturn($tableGateway['mock']);

        $aggregate = $this->getAggregateMock($queryBuilder['mock'], null, $tableGatewayMock['mock']);
        $aggregate->set($fakePropertyValues->name, $fakePropertyValues->value);
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
            $tableGatewayMock = $this->getTableGatewayFactoryMock()['mock'];
        }

        return new Aggregate($queryBuilderMock, $entityMock, $tableGatewayMock, new PropertyFactory);
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
        $query                 = $queryBuilderInterface->shouldReceive('query');

        return [
            'mock'    => $queryBuilderInterface,
            'methods' => compact('select', 'selectFirst', 'insert', 'update', 'beginTransaction', 'rollback', 'commit', 'query')
        ];
    }

    protected function getTableGatewayFactoryMock()
    {
        $tableGatewayFactoryInterface = m::mock('\Moon\Properties\TableGateways\TableGatewayFactory');
        $create                       = $tableGatewayFactoryInterface->shouldReceive('create');

        return [
            'mock'    => $tableGatewayFactoryInterface,
            'methods' => compact('create')
        ];
    }

    protected function getTableGatewayMock()
    {
        $mock           = m::mock('\Moon\Properties\TableGateways\TableGatewayInterface');
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
        $indexStub = new stdClass;
        $indexStub->id = $id;
        $indexStub->name = $name;
        $indexStub->pk = $pk;
        $indexStub->pk_value = $pk_value;

        $properties                   = new stdClass;
        $stubPropertyValues           = new stdClass;
        $stubPropertyValues->id       = 1;
        $stubPropertyValues->index_id = 1;
        $stubPropertyValues->name     = 'name';
        $stubPropertyValues->value    = 'moon';
        $stubPropertyValues->type     = 'Varchar';

        $propertyFactory = new PropertyFactory();

        $property = $propertyFactory->createWithValues($stubPropertyValues);
        $properties->name = new stdClass;
        $properties->name->id = $property->getId();
        $properties->name->index_id = $property->getIndexId();
        $properties->name->name = $property->getName();
        $properties->name->value = $property->getValue();

        $indexStub->cached_properties = json_encode($properties);

        return $indexStub;
    }
}