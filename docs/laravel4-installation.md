## Laravel 4

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