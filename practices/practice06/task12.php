<?php

// Session stores data on server.


//-->login phase
session_start();

$_SESSION["username"] = "InternName";

echo "User logged in";
echo "<br>";

//--> dashboard
echo "Welcome " . $_SESSION["username"];
echo "<br>";

//--> logout

session_destroy();

echo "Logged out";

echo "<br>";

// How Session Works Internally

//1.Browser → Request → Server creates session
//2.Server → Stores data
//3.Server → Sends SessionID cookie
//4.Browser → Sends SessionID next request

// Professional Example (Login Flow)
$userValid = true;

if($userValid){
   $_SESSION["user_id"] = 12;
}

if(isset($_SESSION["user_id"])) {
   echo "Authenticated";
}

echo "<pre>";
print_r($_SESSION);
echo "</pre>"


?>