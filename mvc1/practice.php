<?php

class DataStore
{
    // Static property to hold data
    private static $storage = array();

    // Static method to store data
    public static function set($key, $value)
    {
        self::$storage[$key] = $value;
    }

    // Static method to retrieve data
    public static function get($key)
    {
        if (isset(self::$storage[$key])) {
            return self::$storage[$key];
        }
        return null;
    }

    //  Get all stored data
    public static function getAll()
    {
        return self::$storage;
    }
}


// --------------------
// Using the class
// --------------------

// Storing different types of data
DataStore::set("fname", "xyz");
DataStore::set("lname", "abc");
DataStore::set("age", 25);
DataStore::set("marks", array(80, 90, 75));
DataStore::set("standard", 10);


// Retrieving data
echo DataStore::get("fname");
echo "<br>";

echo DataStore::get("lname");
echo "<br>";

echo DataStore::get("age");
echo "<br>";


// Retrieving array (remains array)
$marks = DataStore::get("marks");
// var_dump(DataStore::get("marks"));

echo "<pre>";
print_r($marks);
echo "</pre>";

$alldata =  DataStore::getAll();

echo "<pre>";
print_r($alldata);
echo "</pre>";


?>
