<?php

/**
 * Setting a key/value pair
 */
$user = User::find(1);
$valeus = $user->getValueAggregate();
$values->set('int', 3);
$values->set('varchar', 'varchar');
$values->set('decimal', 33.00);
$values->set('text', 'more than 255 text......');
$values->persist();

/**
 * Retrieving a value by its key
 */
$user = User::find(1);
$valeus = $user->getValueAggregate();
$values->get('int'); // 3
$values->get('varchar'); // varchar
$values->get('random_key') // throws KeyNotFoundException exception

/**
 * Updating an existing value
 */
$user = User::find(1);
$valeus = $user->getValueAggregate();
$values->set('int', 4); // overrides 3 to 4
$values->update('int', 4) // update can be used as well; same as set when updating a value
$values->persist();

/**
 * Removing a key/value pair
 */

$user = User::find(1);
$valeus = $user->getValueAggregate();
$values->remove('int');
$values->persist();

echo $values->get('int') // throws KeyNotFoundException exception


/**
 * Searching records by a custom value
 */

User::findByValue('age','=',33);
