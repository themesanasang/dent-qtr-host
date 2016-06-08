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
    $response = array("tag" => $tag, "error" => FALSE);

    // checking tag
    if ($tag == 'chwpart') {       
        $response["chwpart"] = array();
        $chwpart = $db->getChwpart();

        while ($row = mysqli_fetch_array($chwpart)) {
            // temp user array
            $tmp = array();
            $tmp["id"] = $row["PROVINCE_ID"];
            $tmp["name"] = $row["PROVINCE_NAME"];
            
            // push ordered items into response array
            array_push($response["chwpart"], $tmp);
        }

        echo json_encode($response);          
    }else if($tag == 'amppart'){       
        $response["amppart"] = array();
        $amppart = $db->getAmppart($_POST['chwpart']);

        while ($row = mysqli_fetch_array($amppart)) {
            // temp user array
            $tmp = array();
            $tmp["id"] = $row["AMPHUR_ID"];
            $tmp["name"] = $row["AMPHUR_NAME"];
            
            // push ordered items into response array
            array_push($response["amppart"], $tmp);
        }

        echo json_encode($response);  
    } else if( $tag == 'tmbpart'){
        $response["tmbpart"] = array();
        $tmbpart = $db->getTmbpart($_POST['chwpart'], $_POST['amppart']);

        while ($row = mysqli_fetch_array($tmbpart)) {
            // temp user array
            $tmp = array();
            $tmp["id"] = $row["DISTRICT_ID"];
            $tmp["name"] = $row["DISTRICT_NAME"];
            
            // push ordered items into response array
            array_push($response["tmbpart"], $tmp);
        }

        echo json_encode($response); 
    } else {
        // user failed to store
        $response["error"] = TRUE;
        $response["error_msg"] = "Unknown 'tag' value. It should be either 'address'";
        echo json_encode($response);
    }
}


?>
