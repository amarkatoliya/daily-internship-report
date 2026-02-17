<?php
  spl_autoload_register(function($class){
    $base = __DIR__;
    $file = str_replace("_","/",$class);
    $path=sprintf("%s/%s.php",$base,$file);
    require_once $path;
    

     //var_dump($path);
  });
    


?>  