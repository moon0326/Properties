# Properties
A framework agnostic package to make it easy to model EAV (Entity Attribute Value) around your objects.

**Warning: This is a pre-release. DO NOT install.**
## Table of Contents

- <a href="#installation">Installation</a>
    - <a href="#composer">Composer</a>
    - <a href="#laravel-4">Laravel 4</a>
    - <a href="#kohana">Kohana 3</a>
- <a href="#usage">Usage</a>
	- <a href="#laravel-4-general-usage">Laravel 4 - General Usage</a>
- <a href="#change-log">Change Log</a>
- <a href="#license">License</a>


## Installation

### Composer

Add `moon/properties` in your "require" section of composer.json.

```json
"moon/properties": "0.1"
```

Run `composer update` to install the package.

### Laravel 4

Simply Implement **Moon\Properties\EntityInterface** and include **Moon\Properties\PropertiesTrait** in your model.

```php

use Moon\Properties\Frameworks\Laravel\PropertiesTrait;
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

#### Running Migrations

Run the provided migrations. The following tables will be generated.

- properties_aggregate
- properties_decimal
- properties_integer
- properties_text
- properties_varchar

```php
php artisan migrate --package=moon/properties
```

## Laravel 4 General Usage


### Setting a New Value
```php
$user = User::find(1);
$properties = $user->getProperties();
$properties->set('name', 'John Doe');
$properties->save();

var_dump($properties->get('name'))

```

### Retriving a Value

```php
$user = User::find(1);
$properties = $user->getProperties();
echo $properties->get('name'); // outputs 'John Doe'
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

### License

Properties is released under the [DBAD](http://www.dbad-license.org) license.