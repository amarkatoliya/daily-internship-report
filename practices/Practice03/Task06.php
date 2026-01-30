<?php

// use inbuilt functions

//-----> Task 1

$example = "  Hello World ";

$cleanExample = strtolower(trim($example)); //  it will be convert str to lowercase and trim spaces

echo substr_replace($cleanExample,"PHP",6);   // (var,changed_tex,staring_index_of_delete)

echo $example . "<br>";


$numbers = [1, 3, 5, 7, 9];

if (in_array(5, $numbers)) {
    echo "5 exists in the array <br>";
} else {
    echo "5 does not exist in the array <br>";
}

array_push($numbers, 11);   // push in arr

$moreNumbers = [13, 15];

$mergedArray = array_merge($numbers, $moreNumbers);  // array merge inbuilt fun


// ------output----------

echo "<pre>";
print_r($mergedArray);
echo "</pre>";


// ---->example

//  input
$username = "  Amar123 ";
$roles = ["admin", "editor", "user"];

// Clean the username
$cleanUsername = strtolower(trim($username));

// Replace numbers from username
$cleanUsername = str_replace("123", "", $cleanUsername);

// Check if admin role exists
if (in_array("admin", $roles)) {
    echo "Admin role exists <br>";
}

//  Add new role
array_push($roles, "manager");

// Merge with  role list
$extraRoles = ["guest"];
$allRoles = array_merge($roles, $extraRoles);

//----  Output


echo "Clean Username: " . $cleanUsername . "<br>";

echo "<pre>";
print_r($allRoles);
echo "</pre>";


