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
    require_once '../DB_Functions.php';
    $db = new DB_Functions();

    // response Array
    $response = array("tag" => $tag, "error" => FALSE);

    // checking tag
    if ($tag == 'screen') {
        
        // Request type is Register new screen
        $create_by = $_POST['create_by'];
        $cid = $_POST['cid'];
        $fullname = $_POST['fullname'];
        $age = $_POST['age'];
        $address = $_POST['address'];
        $pic_logo = $_POST['pic_logo'];
        $pic_1 =  $_POST['pic_1'];
        $pic_2 = $_POST['pic_2'];
        $pic_3 = $_POST['pic_3'];
        $regdate = date('Y-m-d H:i:s');
             
        $screen = $db->storeScreen($create_by, $cid, $fullname, $age, $address, $pic_logo, $pic_1, $pic_2, $pic_3, $regdate);
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
    
    }else if($tag == 'screen-edit'){
           
        $id_edit = $_POST['id_edit'];
        $cid = $_POST['cid'];
        $fullname = $_POST['fullname'];
        $age = $_POST['age'];
        $address = $_POST['address'];
        $pic_logo = $_POST['pic_logo'];
        $pic_1 =  $_POST['pic_1'];
        $pic_2 = $_POST['pic_2'];
        $pic_3 = $_POST['pic_3'];
        
        $screen = $db->updateScreen($id_edit, $cid, $fullname, $age, $address, $pic_logo, $pic_1, $pic_2, $pic_3);
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
           
    }else if($tag == 'screen-delete'){
        
    } else {
        // user failed to store
        $response["error"] = TRUE;
        $response["error_msg"] = "Unknown 'tag' value. It should be either 'screen'";
        echo json_encode($response);
    }
}



?>
