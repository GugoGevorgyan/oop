<?php
namespace vendor\src;
interface DBMethods{
    /**
     * @param $args ['col1', 'col2', ... ], 'col1', ''
     * @return mixed
     */
    public function select($args);

    /**
     * @param $table_name the current table name
     * @return mixed
     */
    public function from($table_name);

    /**
     * @param $table_name the current table name
     * @param array $args ['col'=> 'value', 'col1' => 'value1', ...]
     * @return mixed
     */
    public function insert($table_name, array $args);

    /**
     * @param $table_name
     * @param $args ['col_name' => 'col_value',];
     * @return mixed
     */
    public function update($table_name,$args);

    /**
     * @return mixed
     */
    public function delete();

    /**
     * @param $create_query
     * @return mixed
     */
    public function createTable($create_query);

    /**
     * @param array $condition
     [
     'col_name' => 'id',
     'col_value' => 5,
     'col_value1' => 10,
     'con_type' => 'between'
     ]
     * @return mixed
     */
    public function where(array $condition);

    public function andWhere(array  $condition);

    /**
     * @param array $args
     * @return mixed
     */
    public function orderBy(array $args);

    public function beginTransaction();

    public function commit();

    public function rollBack();
}