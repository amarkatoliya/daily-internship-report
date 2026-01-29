<?php

// Foreach example with associative array
$players = [
    "Gill" => 90,
    "Kishan" => 40,
    "Abhishek" => 50
];


foreach ($players as $player => $run) {
    echo "$player created $run <br>";
}

// --- multi table of 5 

$num = 5;

echo "Multiplication table of 5: <br>";

for ($i = 1; $i <= 10; $i++) {
    echo $num . " X " . $i . " = " . ($num * $i) . "<br>";
}

echo "<br>";

require __DIR__ . '/../practice01/task02.php';
// ------ at this call required file also executed

// var_dump($person);
echo "<br>";


foreach ($person as $key => $val) {
    if (is_array($val)) {
        $val = implode(", ", $val);   // arr to str conversion
    }
    echo "$key : $val <br>";
}


echo "<br>";

$numbers = [10, 20, 30, 40];

for ($i = 0; $i < count($numbers); $i++) {
    echo $numbers[$i] . "<br>";
}