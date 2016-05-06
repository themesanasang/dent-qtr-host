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
    if($tag == 'profile'){       
        $username = $_POST['username'];
        
        $user = $db->getDetailProfile($username);
        if ($user != false) {                     
            $row = mysqli_fetch_array($user);
            
            $response["error"] = FALSE;          
            $response["username"] = $row["username"];
            $response["name"] = $row["name"];           
            $response["address"] = $row["address"];         
                      
            echo json_encode($response);
        } else {
            // user not found
            // echo json with error = 1
            $response["error"] = TRUE;
            $response["error_msg"] = "ไม่สามารถแสดงข้อมูลส่วนตัวได้!";
            echo json_encode($response);
        }
    } else if( $tag == 'profile-edit'){
        
        $username = $_POST['username'];
        $name = $_POST['name'];
        $password = $_POST['password'];
        $address = $_POST['address'];
        
        $profile = $db->updateProfile($username, $name, $password, $address);
        if ($profile) {
            // user stored successfully
            $response["error"] = FALSE;
            echo json_encode($response);
        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Error occured in Edit Profile";
            echo json_encode($response);
        } 
        
    } else {
        // user failed to store
        $response["error"] = TRUE;
        $response["error_msg"] = "Unknown 'tag' value. It should be either 'profile'";
        echo json_encode($response);
    }
}


?>
