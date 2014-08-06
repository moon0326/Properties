## Laravel 4 General Usage


### Setting a New Value
```php
$user = User::find(1);
$properties = $user->getProperties();
$properties->set('name', 'John Doe');
$properties->save();
```

### Retriving a Value

```php
$user = User::find(1);
$properties = $user->getProperties();

var_dump($properties->get('name')); // string 'John Doe' (length=7)
```

### Updating Existing Value
When updating a key, you have two choices. You can simply use **set($key, $value)** method or **update($key, $value)** method explicitly

```php
$user = User::find(1);
$properties = $user->getProperties();
$properties->set('name',' John'); // Updates John Doe to John
$properties->save();
```

### Deleting Existing Value
```php
$user = User::find(1);
$properties = $user->getProperties();
$properties->delete('name');
$properties->save();
```

### Getting All the Existing Keys

```php
$user = User::find(1);
$properties = $user->getProperties();
$properties->set('name', 'John Doe');
$properties->set('age', 9999);
$properties->set('weight', 180);
$properties->save();

print_r($properties->keys()); // returns name, age, and weight as an array
```

### Destorying
```php
$user = User::find(1);
$properties = $user->getProperties();

var_dump($properties->getIndexId()); // int 1

$properties->destroy();

$properties->set('type', 'cat');
$properties->save();

var_dump($properties->getIndexId()); // int 2; notice that we have a new id now.


```