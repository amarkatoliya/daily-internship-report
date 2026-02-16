<?php

// Cookies & Sessions are core backend concepts

// Setting Cookie (Different Example)

// Must be before HTML

setcookie("cname", "dark_mode", time() + 3600, "/");
echo "Preference saved";
echo "<br>";


//----> Explanation

// name   → cname
// value  → dark_mode
// 3600   → 1 hour
// "/"    → entire website



// Reading Cookie

if(isset($_COOKIE["cname"])) {
    echo "Preference: " . $_COOKIE["cname"];
} else {
    echo "No preference set";
}

?>