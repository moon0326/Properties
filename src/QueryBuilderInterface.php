<?php namespace Moon\Properties;

interface QueryBuilderInterface
{
    /**
     * @param  string 	  $table  name of the table
     * @param  array  	  $wheres an array of key/value where clauses
     * @return Array|NULL should return an array ob rows in stdClass format or NULL if not found
     */
    public function select($table, array $wheres);

    /**
     * @param  string 		 $table  name of the table
     * @param  array  		 $wheres an array of key/value where clauses
     * @return stdClass|NULL should return an object that represents a table columns or NULL if not found
     */
    public function selectFirst($table, array $wheres);

    /**
     * @param  string $table  name of the table
     * @param  array  $values key/value pairs for the insert statement
     * @return int    should return inserted id
     */
    public function insert($table, array $values);

    /**
     * @param  string $table  name of the table
     * @param  array  $values key/value paris for the update statement
     * @param  int    $id    id of the record to be updated
     * @return void
     */
    public function update($table, array $values, $id);

    /**
     * @param  string $table  name of the table
     * @param  array  $values key/value where clauses
     * @return void
     */
    public function delete($table, array $wheres);


    public function beginTransaction();
    public function rollback();
    public function commit();
}
