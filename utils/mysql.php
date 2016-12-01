<?php

class mysql
{
    protected static $connection;

    public static function connect() {
        if (!isset(self::$connection))
            self::$connection = new mysqli(config::DB_SERVER, config::DB_USER, config::DB_PASS, config::DB_NAME);

        echo self::$connection->error;
        self::$connection->set_charset("utf8");
        return self::$connection;
    }

    /**
     * Selects items from database.
     * @param $query
     * @return array|bool array if results were found; otherwise false if failed.
     */
    public static function select($query) {
        $result = mysql::query($query);
        if($result == false)
            return false;

        $rows = array();
        while($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Queries database.
     * @param $query
     * @return bool|mysqli_result false if query failed.
     */
    public static function query($query) {
        $connection = mysql::connect();
        $result = $connection->query($query);
        return $result;
    }

    /**
     * Escapes field to avoid database injections.
     * @param $field
     * @return string
     */
    public static function escape_variable($field) {
        $connection = mysql::connect();
        return mysqli_real_escape_string($connection, $field);
    }

    /**
     * Fetch the last error from the database
     * @return string Database error message
     */
    public static function error() {
        $connection = mysql::connect();
        return $connection->error;
    }

    private static function multidimensionalArrayMap( $func, $arr )
    {
        $newArr = array();
        foreach( $arr as $key => $value )
        {
            $newArr[ $key ] = ( is_array( $value ) ? mysql::multidimensionalArrayMap( $func, $value ) : $func( $value ) );
        }
        return $newArr;
    }

}