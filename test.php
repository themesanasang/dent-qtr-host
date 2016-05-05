<?php
error_reporting(E_ALL ^ E_DEPRECATED);
date_default_timezone_set('Asia/Bangkok');

// include DB_function
require_once 'DB_Functions.php';
$db = new DB_Functions();
    


$user = $db->getPatientBySearch('g', 'a');

echo count($user);    
           

       
            
      

?>
