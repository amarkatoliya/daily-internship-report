<?php

 // Magic methods are special and that  methods are PHP automatically calls when certain actions happen.

 // They always start with: __

class Profile {

    private $data = [];

    //-> 1 Constructor
    public function __construct($name) {     // Runs automatically when object is created.
        echo "Profile created for $name <br>";
        $this->data["name"] = $name;
    }

    //-> 2 Destructor
    public function __destruct() {         // Runs when script ends or object removed.
        echo "<br>Profile object destroyed suceess";
    }

    //-> 3 Set undefined property
    public function __set($key, $value) {    // Runs when script ends or object removed.    
        $this->data[$key] = $value;
    }

    //-> 4 Get undefined property
    public function __get($key) {         // Triggered when reading inaccessible property.
        return $this->data[$key] ?? "Property '$key' not found";
    }

    //-> 5 Object to string
    public function __toString() {       // Triggered when object treated as string.
        return json_encode($this->data);
    }

    //-> 6 Call undefined method
    public function __call($method, $args) {     // Triggered when undefined method called. 
        return "Method '$method' does not exist";
    }
}

$profile = new Profile("Ravi");

$profile->age = 25;
$profile->city = "Delhi";

echo $profile->name;
echo "<br>";

echo $profile->age;
echo "<br>";

echo $profile;
echo "<br>";

echo $profile->updateProfile();

?>