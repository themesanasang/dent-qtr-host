<?php
error_reporting(E_ALL ^ E_DEPRECATED);
date_default_timezone_set('Asia/Bangkok');

// include DB_function
require_once 'DB_Functions.php';
$db = new DB_Functions();
    


$user = $db->updateProfile('a1', 'game game', '1', 'nth');

if($user){
    echo 'ok';
}else{
    echo 'no';
}
           

       
            
      

?>
