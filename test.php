<?php
error_reporting(E_ALL ^ E_DEPRECATED);
date_default_timezone_set('Asia/Bangkok');

require_once 'db_functions.php';
$db = new DB_Functions();

        $create_by = 'g';
        $cid = '1111111';
        $fullname = 'test';
        $age = '';
        $address = '';
        $chwpart = 'นครราชสีมา';
        $amppart = 'โนนไทย';
        $tmbpart = 'โนนไทย';
        $mobile = '';
        $pic_logo = '';
        $pic_1 =  '';
        $pic_2 = '';
        $pic_3 = '';       
        $pic_4 = '';
        $pic_5 = '';
        $pic_6 = '';       
        $regdate = date('Y-m-d H:i:s');
             
        $screen = $db->storeScreen($create_by, $cid, $fullname, $age, $address, $chwpart, $amppart, $tmbpart, $mobile, $pic_logo, $pic_1, $pic_2, $pic_3, $pic_4, $pic_5, $pic_6, $regdate);
        if ($screen) {
            // user stored successfully
            $response["error"] = FALSE;
            echo json_encode($response);
        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Error occured in Screen";
            echo json_encode($response);
        }           

?>
