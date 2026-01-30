<?php

declare(strict_types=1);   // stict type

function calTotal(float $price, int $qty): float
{
    return $price *  $qty;
}

echo calTotal(29.88, 24);

echo "<br>";
// echo calTotal(10,10);

//  echo calTotal(545, '1'); // This would be TypeError


// local vs global vars

//-----> local var

function showLocal()
{
    $local = "Hi i am local variable";
    echo $local . "<br>";     // working
}

showLocal();

// echo $local;   // error because we are calling local variable outside of its scope


//----> global var

$siteName = "My site";
$siteNametwo = "My site";

function showGlobal()
{
    global $siteName;
    echo $siteName;
}

// othrer way to use

function showGlobaltwo()
{
    echo $GLOBALS['siteNametwo'];
    echo "<br>";
}

showGlobal(); // gives My site
showGlobaltwo();


$count = 10; // Global variable

function testScope()
{
    $count = 5;  // Local variable
    echo "Local count : $count<br>";
}

testScope();
echo "Global count: $count";

echo "<br>";

// out --  local 5
// out --  global 10


// Genrral practice example 

$taxRate = 0.18;

function calculatePrice(float $price): float
{
    // Local variable
    $discount = 50;

    // Access global variable
    global $taxRate;

    $finalPrice = $price - $discount;
    $finalPrice += $finalPrice * $taxRate;

    return $finalPrice;
}

echo calculatePrice(100000);