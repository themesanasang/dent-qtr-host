<?php
error_reporting(E_ALL ^ E_DEPRECATED);
date_default_timezone_set('Asia/Bangkok');
/**
 * File to handle all API requests
 * Accepts GET and POST
 * 
 * Each request will be identified by TAG
 * Response will be JSON data
 */

/**
 * check for POST request 
 */
if (isset($_POST['tag']) && $_POST['tag'] != '') {
    // get tag
    $tag = $_POST['tag'];

    // include DB_function
    require_once '../db_functions.php';
    $db = new DB_Functions();

    // response Array
    $response = array();

    // checking tag
    if ($tag == 'screen') {
        
        // Request type is Register new screen
        $create_by = $_POST['create_by'];
        $cid = $_POST['cid'];
        $fullname = $_POST['fullname'];
        $age = $_POST['age'];
        $address = $_POST['address'];
        $chwpart = $_POST['chw'];
        $amppart = $_POST['amp'];
        $tmbpart = $_POST['tmb'];
        $mobile = $_POST['mobile'];
        $pic_logo = $_POST['pic_logo'];
        $pic_1 =  $_POST['pic_1'];
        $pic_2 = $_POST['pic_2'];
        $pic_3 = $_POST['pic_3'];       
        $pic_4 = $_POST['pic_4'];
        $pic_5 = $_POST['pic_5'];
        $pic_6 = $_POST['pic_6'];       
        $regdate = date('Y-m-d H:i:s');
             
        $screen = $db->storeScreen($create_by, $cid, $fullname, $age, $address, $chwpart, $amppart, $tmbpart, $mobile, $pic_logo, $pic_1, $pic_2, $pic_3, $pic_4, $pic_5, $pic_6, $regdate);
        
        if ($screen) {
            echo 'success';
        } else {
            echo 'เกิดข้อผิดพลาด! ไม่สามารถบันทึกข้อมูลคัดกรองได้';
        }       
    
    }else if($tag == 'screen-edit'){
           
        $id_edit = $_POST['id_edit'];
        $cid = $_POST['cid'];
        $fullname = $_POST['fullname'];
        $age = $_POST['age'];
        $address = $_POST['address'];
        $mobile = $_POST['mobile'];
        $pic_logo = $_POST['pic_logo'];
        $pic_1 =  $_POST['pic_1'];
        $pic_2 = $_POST['pic_2'];
        $pic_3 = $_POST['pic_3'];
        $pic_4 = $_POST['pic_4'];
        $pic_5 = $_POST['pic_5'];
        $pic_6 = $_POST['pic_6'];
        
        $screen = $db->updateScreen($id_edit, $cid, $fullname, $age, $address, $mobile, $pic_logo, $pic_1, $pic_2, $pic_3, $pic_4, $pic_5, $pic_6);
        
        if ($screen) {
            echo 'success';
        } else {
            echo 'เกิดข้อผิดพลาด! ไม่สามารถแก้ไขข้อมูลคัดกรองได้';
        }     
           
    }else if($tag == 'screen-delete'){

        $id_edit = $_POST['id_edit'];
        $screen = $db->deleteScreen($id_edit);
        
        if ($screen) {
            echo 'success';
        } else {
            echo 'เกิดข้อผิดพลาด! ไม่สามารถลบข้อมูลคัดกรองได้';
        }   

    } else {
        // user failed to store
        $response["error"] = TRUE;
        $response["error_msg"] = "Unknown 'tag' value. It should be either 'screen'";
        echo json_encode($response);
    }
}



?>
