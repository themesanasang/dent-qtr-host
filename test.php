<?php
error_reporting(E_ALL ^ E_DEPRECATED);
date_default_timezone_set('Asia/Bangkok');

// include DB_function
require_once 'DB_Functions.php';
$db = new DB_Functions();
    
$day = '21-04-2559 10:40:04';
list($day, $month, $year,$h,$i,$s) = split('[-  :]', $day);  
   
$username = 'g';
$cid = 6;
$regdate = (($year) - 543).'-'.$month.'-'.$day.' '.$h.':'.$i.':'.$s;

$user = $db->getDetailPatient($username, $cid, $regdate);
echo $user;
if ($user != false) {
    
    $response["orders"] = array();

    while ($row = mysqli_fetch_array($user)) {
        $response["error"] = FALSE;
        $response["id"] = $row["id"];
        $response["cid"] = $row["cid"];
        $response["fullname"] = $row["fullname"];
        
    }

    echo json_encode($response);
} else {
    // user not found
    // echo json with error = 1
    $response["error"] = TRUE;
    $response["error_msg"] = "ไม่สามารถแสดงข้อมูลคัดกรองได้!";
    echo json_encode($response);
}


?>
