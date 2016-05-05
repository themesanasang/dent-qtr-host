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
    if ($tag == 'patient') {
        $username = $_POST['username'];

        $user = $db->getPatientByUsername($username);
        if ($user != false) {
            
            $response["orders"] = array();

            while ($row = mysqli_fetch_array($user)) {
                // temp user array
                $item = array();
                $item["cid"] = $row["cid"];
                $item["fullname"] = $row["fullname"];
                $item["pic_logo"] = (($row["pic_logo"] != "")?$row["pic_logo"]:"nodata");
                $item["screen_by"] = $row["create_by"];
                $item["regdate"] = date('d-m', strtotime($row["regdate"])).'-'.((date('Y', strtotime($row["regdate"])))+(543)).' '.date('H:i:s', strtotime($row["regdate"]));
       
                // push ordered items into response array
                array_push($response["orders"], $item);
           }

            echo json_encode($response);
            
        } else {
            // user not found
            // echo json with error = 1
            $response["error"] = TRUE;
            $response["error_msg"] = "ไม่สามารถแสดงรายการคัดกรองได้!";
            echo json_encode($response);
        }
    
    }else if($tag == 'detail-patient'){       
        $username = $_POST['username'];
        $cid = $_POST['cid'];
        $pday = $_POST['pday'];
        
        list($day, $month, $year,$h,$i,$s) = split('[-  :]', $pday);
        $regdate = (($year) - 543).'-'.$month.'-'.$day.' '.$h.':'.$i.':'.$s;
        
        $user = $db->getDetailPatient($username, $cid, $regdate);
        if ($user != false) {                     
            $row = mysqli_fetch_array($user);
            
            $response["error"] = FALSE;
            $response["id"] = $row["id"];
            $response["cid"] = $row["cid"];
            $response["fullname"] = $row["fullname"];
            $response["age"] = $row["age"];
            $response["address"] = $row["address"];
            $response["pic_logo"] = $row["pic_logo"];
            $response["pic_1"] = $row["pic_1"];
            $response["pic_2"] = $row["pic_2"];
            $response["pic_3"] = $row["pic_3"];
                      
            echo json_encode($response);
        } else {
            // user not found
            // echo json with error = 1
            $response["error"] = TRUE;
            $response["error_msg"] = "ไม่สามารถแสดงข้อมูลคัดกรองได้!";
            echo json_encode($response);
        }
    } else if( $tag == 'patient-search'){
        $username = $_POST['username'];
        $txtsearch = $_POST['txtsearch'];

        $user = $db->getPatientBySearch($username, $txtsearch);
        if ($user != false) {
            
            $response["orders"] = array();

            while ($row = mysqli_fetch_array($user)) {
                // temp user array
                $item = array();
                $item["cid"] = $row["cid"];
                $item["fullname"] = $row["fullname"];
                $item["pic_logo"] = (($row["pic_logo"] != "")?$row["pic_logo"]:"nodata");
                $item["screen_by"] = $row["create_by"];
                $item["regdate"] = date('d-m', strtotime($row["regdate"])).'-'.((date('Y', strtotime($row["regdate"])))+(543)).' '.date('H:i:s', strtotime($row["regdate"]));
       
                // push ordered items into response array
                array_push($response["orders"], $item);
           }

            echo json_encode($response);
            
        } else {
            // user not found
            // echo json with error = 1
            $response["error"] = TRUE;
            $response["error_msg"] = "ไม่สามารถแสดงรายการคัดกรองได้!";
            echo json_encode($response);
        }
        
    } else {
        // user failed to store
        $response["error"] = TRUE;
        $response["error_msg"] = "Unknown 'tag' value. It should be either 'patient'";
        echo json_encode($response);
    }
}


?>
