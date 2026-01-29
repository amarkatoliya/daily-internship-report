<?php

//------ 1

$marks = 67;

if ($marks > 90) {
    echo "Result: A";
} elseif ($marks > 70) {
    echo "Result: B";
} elseif ($marks > 50) {
    echo "Result: C";
} else {
    echo "Result: Fail";
}

echo "<br>";

// ----2

$day = 3;

switch($day) {
    case 1:
        echo "Monday";
        break;
    case 2:
        echo "Tuesday";
        break;
    case 3:
        echo "Wednesday";
        break;
    case 4:
        echo "Thursday";
        break;
    case 5:
        echo "Friday";
        break;
    case 6:
        echo "Saturday";
        break;
    case 7:
        echo "Sunday";
        break;
}
echo "<br>";

// --- 3

$age = 20;

if ($age < 18) {
    echo "Minor";
} elseif ($age >= 18 && $age < 60) {
    echo "Adult";
} else {
    echo "Senior Citizen";
}