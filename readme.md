## Properties
A framework agnostic package to make it easy to model EAV (Entity Attribute Value) around existing tables.

## Installing on Laravel 4

Implement and use a trait in your model.

```php

use Moon\Properties\Facades\Laravel\PropertiesTrait;
use Moon\Properties\EntityInterface;

class User extends Eloquent implements UserInterface, RemindableInterface, EntityInterface { // Implement EntityInterface

	use UserTrait, RemindableTrait;
	use PropertiesTrait; // Include PropertiesTrait

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

}
```

### Running Migrations

```php
php artisan migrate --package=moon/properties
```

### Setting a Key/Value 

```php

$user = User::find(1);
$properties = $user->getProperties();
$properties->set('name', 'John Doe');
$properties->save();

```

### Retriving a Value by Its Key

```php
$user = User::find(1);
$properties = $user->getProperties();
echo $properties->get('name'); // outputs 'John Doe'
```

### Updating a Value by Its Key
When updating a key, you have two choices. You can simply use **set($key, $value)** method or **update($key, $value)** method explicitly

```php
$user = User::find(1);
$properties = $user->getProperties();
$properties->set('name',' John'); // Updates John Doe to John
$properties->ssave();
```

### Deleting a Value by its Key
```php
$user = User::find(1);
$properties = $user->getProperties();
$properties->delete('name');
$properties->ssave();
```

### Getting All the Keys

```php
$user = User::find(1);
$properties = $user->getProperties();
$properties->set('name', 'John Doe');
$properties->set('age', 9999);
$properties->set('weight', 180);
$properties->save();

print_r($properties->keys()); // returns name, age, and weight as an array
```