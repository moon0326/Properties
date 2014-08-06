<?php

use \Mockery as m;

use \Moon\Properties\Frameworks\Native\QueryBuilder;
use \Moon\Properties\Aggregate;
use \Moon\Properties\TableGateways\TableGatewayFactory;
use \Moon\Properties\Properties\PropertyFactory;
use \Moon\Properties\EntityInterface;

class EntityTest implements EntityInterface
{
    public function getIdentifierName()
    {
    	return 'id';
    }

    public function getIdentifier()
    {
    	return 1;
    }

    public function getName()
    {
    	return 'integrationTest';
    }
}

class Base extends \PHPUnit_Framework_TestCase
{
	protected $queryBuilder;
	protected $aggregate;

	public function setUp()
	{
        $this->queryBuilder = new QueryBuilder([
			'host' => 'localhost',
			'database' => 'phpvideo',
			'user' => 'root',
			'password' => '1234'
        ]);

        $this->aggregate = new Aggregate(
            $this->queryBuilder,
            new EntityTest(),
            new TableGatewayFactory(),
            new PropertyFactory()
        );
	}

    public function tearDown()
    {
    	$tables = ['aggregate', 'decimal', 'integer', 'text', 'varchar'];
    	$connection = $this->queryBuilder->getConnection();

    	foreach ($tables as $table) {
    		$a = $connection->query('delete from properties_'.$table);
    	}

        m::close();
    }

    public function testSettingSingleValue()
    {
        $this->aggregate->set('name', 'moon');
        $this->aggregate->save();


        $savedValue = $this->queryBuilder->selectFirst('properties_varchar', ['index_id'=>$this->aggregate->getIndexId(), 'name'=>'name']);


        $this->assertTrue(isset($savedValue));
        $this->assertEquals('moon', $savedValue->value);
    }

    public function testSettingMultipleValues()
    {
        $this->aggregate->set('name','moon');
        $this->aggregate->set('weight',99999);
        $this->aggregate->save();

        $indexId = $this->aggregate->getIndexId();

        $name = $this->queryBuilder->selectFirst('properties_varchar',['index_id'=>$indexId, 'name'=>'name']);
        $weight = $this->queryBuilder->selectFirst('properties_integer',['index_id'=>$indexId, 'name'=>'weight']);

        $this->assertTrue(isset($name));
        $this->assertTrue(isset($weight));

        $this->assertEquals('moon',$name->value);
        $this->assertEquals(99999, $weight->value);
    }

    public function testUpdatingValue()
    {
        $this->aggregate->set('name', 'moon')->save();
        $this->aggregate->set('name', 'moon2')->save();

        $name = $this->getVarchar('name');
        $this->assertTrue(isset($name));
        $this->assertEquals('moon2',$name->value);
    }

    public function testDeletingValue()
    {
        $this->aggregate->set('name','moon')->save();
        $this->aggregate->delete('name')->save();

        $this->assertEquals(0, count($this->aggregate->all()));
    }

    public function testDestory()
    {
        $originalAggregateId = $this->aggregate->getIndexId();
        $this->aggregate->destroy();

        $aggregateId = $this->aggregate->getIndexId();
        $this->assertTrue($originalAggregateId !== $aggregateId);
    }

    public function testAggregateShouldBeReusableAfterDestroy()
    {
        $originalAggregateId = $this->aggregate->getIndexId();
        $this->aggregate->destroy();
        $this->aggregate->set('name', 'moon');
        $this->aggregate->save();

        $this->assertEquals($this->aggregate->get('name'), 'moon');
        $this->assertTrue($originalAggregateId !== $this->aggregate->getIndexId());
    }

    /**
     * Helpers
     */
    protected function getByKey($type, $key, $returnFirst)
    {
        $rows = $this->queryBuilder->select('properties_' . $type, ['index_id'=>$this->aggregate->getIndexId(), 'name'=>$key]);

        if ($returnFirst) {
            return $rows[0];
        }

        return $rows;
    }

    protected function getVarchar($key, $returnFirst = true)
    {
        return $this->getByKey('varchar', $key, $returnFirst);
    }

    protected function getDecimal($key, $returnFirst = true)
    {
        return $this->getByKey('decimal', $key, $returnFirst);
    }

    protected function getInteger($key, $returnFirst = true)
    {
        return $this->getByKey('integer', $key, $returnFirst);
    }

    protected function getText($key, $returnFirst = true)
    {
        return $this->getByKey('text', $key, $returnFirst);
    }
}