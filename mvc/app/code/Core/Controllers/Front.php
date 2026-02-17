<?php
class Core_Controllers_Front{
 function __Construct(){
        $admin = new Core_Controllers_Admin(); 
        echo "<pre>";
        print_r($admin);
        echo "</pre>";
 }
}

?>