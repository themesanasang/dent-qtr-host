<?php
error_reporting(E_ALL ^ E_DEPRECATED);
date_default_timezone_set('Asia/Bangkok');

// include DB_function
require_once 'DB_Functions.php';
$db = new DB_Functions();
    
$day = '21-04-2559 10:40:04';
//list($day, $month, $year,$h,$i,$s) = split('[-  :]', $day);  
   
list($y, $m, $d ,$h, $i, $s) = multiexplode(array("-"," ",":"), trim($day)); 

echo $y.'-'.$m.'-'.$d;


function multiexplode ($delimiters,$string) {
   
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}

?>
