<?php

//string
$name = "none";

// integer
$age = 20;

// float
$percentage = 99.99;

// boolean
$isStudent = true;

// Array 
$subject = ["maths","english","physics"];

// null
$caste = null;

// output -->

echo gettype($name) . "<br>";
echo gettype($age). "<br>";
echo gettype($percentage). "<br>";
echo gettype($isStudent). "<br>";
echo gettype($subject). "<br>";
echo gettype($caste). "<br>";


// -- work --

$greeting = "Hello!";

$year = 2026;

// concatination 

echo $greeting . ", the year is " . $year . "<br>"; 

$isActive = false;
echo "isActive value:" . ($isActive ? "true" : "false");

?>