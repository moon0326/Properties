## Native - General Usage

In order to use Properties with your native PHP codebase, you need to create a method in your object.

Let's say you have a db-aware object, which can save it self into a table and you want to use Properties like below.

```php
class aObject extends aBaseClass
{
	
}

$aObject = new aObject();
$properties $aObject->getProperties();
$properties->set('weather', 'sunny');
$properties->save();

```

You need to create "getProperties" method and return an instance of ```Moon\Properties\Aggregate```

Here's a sample ```aBaseClass```

```php
class aBaseClass
{

    public function getProperties()
    {
		$conn = new PDO(
            'mysql:host='.$config['host'].';dbname='.$config['database'],
            $config['user'],
            $config['password']
        );
        
        $queryBuilder = new QueryBuilder($conn);

        return new Aggregate(
            $queryBuilder,
            $this,
            new TableGatewayFactory()
        );
    }
}
```

Once you have ```getProperties```, the usage is same as other frameworks.