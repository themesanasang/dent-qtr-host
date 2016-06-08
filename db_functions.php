<?php
error_reporting(E_ALL ^ E_DEPRECATED);
date_default_timezone_set('Asia/Bangkok');

class db_functions {

    private $db;

    // constructor
    function __construct() {
        require_once 'db_connect.php';
        // connecting to database
        $this->db = new DB_Connect();
	    $this->db->connect();
    }

    // destructor
    function __destruct() {
        
    }






    /**
     * Store Screen
     */
    public function storeScreen($create_by, $cid, $fullname, $age, $address, $chwpart, $amppart, $tmbpart, $mobile, $pic_logo, $pic_1, $pic_2, $pic_3, $pic_4, $pic_5, $pic_6, $regdate) {
        $created_at = $regdate;
        $updated_at = $regdate;
        $last_screen = $regdate;

        $chwpart = $this->getIDChw($chwpart);
        $amppart = $this->getIDAmp($chwpart, $amppart);
        $tmbpart = $this->getIDTmb($chwpart, $amppart, $tmbpart); 
        
        $result = mysqli_query($this->db->con,"INSERT INTO screen(cid, fullname, age, address, chwpart, amppart, tmbpart, mobile, pic_logo, pic_1, pic_2, pic_3, pic_4, pic_5, pic_6, regdate, create_by, updated_at, created_at) VALUES('$cid', '$fullname', '$age', '$address','$chwpart','$amppart','$tmbpart', '$mobile', '$pic_logo', '$pic_1', '$pic_2', '$pic_3', '$pic_4', '$pic_5', '$pic_6', '$regdate', '$create_by', '$updated_at', '$created_at')") or die(mysqli_error($this->db));
        // check for result
        if ($result) {

            $chk_patient = mysqli_query($this->db->con,"SELECT * FROM patient WHERE cid = '$cid'") or die(mysqli_connect_errno());
            $no_of_rows_patient = mysqli_num_rows($chk_patient);
            if( $no_of_rows_patient == 0 ){
                mysqli_query($this->db->con,"INSERT INTO patient(cid, fullname, address, chwpart, amppart, tmbpart, mobile, regdate, last_screen, updated_at, created_at) VALUES('$cid', '$fullname', '$address','$chwpart','$amppart','$tmbpart', '$mobile', '$regdate', '$last_screen', '$updated_at', '$created_at')") or die(mysqli_error($this->db));
            }
            
           return true;
        } else {
            return false;
        }
    }
    
    
    
    
    
    /**
     * Update Screen
     */
    public function updateScreen($id_edit, $cid, $fullname, $age, $address, $mobile, $pic_logo, $pic_1, $pic_2, $pic_3, $pic_4, $pic_5, $pic_6) {
        $updated_at = date('Y-m-d H:i:s');
        
        $result = mysqli_query($this->db->con,"UPDATE screen SET cid='$cid', fullname='$fullname', age='$age', address='$address', mobile='$mobile', updated_at='$updated_at' WHERE id=$id_edit ") or die(mysqli_error($this->db));
                       
        // check for result
        if ($result) {
            
            if( $pic_logo != '' ){
                mysqli_query($this->db->con,"UPDATE screen SET pic_logo='$pic_logo' WHERE id=$id_edit ") or die(mysqli_error($this->db));
            }
            
            if( $pic_1 != '' ){
                mysqli_query($this->db->con,"UPDATE screen SET pic_1='$pic_1' WHERE id=$id_edit ") or die(mysqli_error($this->db));
            }
            
            if( $pic_2 != '' ){
                mysqli_query($this->db->con,"UPDATE screen SET pic_2='$pic_2' WHERE id=$id_edit ") or die(mysqli_error($this->db));
            }
            
            if( $pic_3 != '' ){
                mysqli_query($this->db->con,"UPDATE screen SET pic_3='$pic_3' WHERE id=$id_edit ") or die(mysqli_error($this->db));
            }
            
            if( $pic_4 != '' ){
                mysqli_query($this->db->con,"UPDATE screen SET pic_4='$pic_4' WHERE id=$id_edit ") or die(mysqli_error($this->db));
            }
            
            if( $pic_5 != '' ){
                mysqli_query($this->db->con,"UPDATE screen SET pic_5='$pic_5' WHERE id=$id_edit ") or die(mysqli_error($this->db));
            }
            
            if( $pic_6 != '' ){
                mysqli_query($this->db->con,"UPDATE screen SET pic_6='$pic_6' WHERE id=$id_edit ") or die(mysqli_error($this->db));
            }
            
           return true;
        } else {
            return false;
        }
    }
    
    
    
    
     /**
     * Delete Screen
     */
    public function deleteScreen($id_edit) {
        $result = mysqli_query($this->db->con,"DELETE FROM screen WHERE id=$id_edit") or die(mysqli_error($this->db));
        // check for result
        if ($result) {
           return true;
        } else {
            return false;
        }
    }






    /**
     * Get user by username and password
     */
    public function getUserByUsernameAndPassword($username, $password) {
        $result = mysqli_query($this->db->con,"SELECT * FROM users WHERE username = '$username'") or die(mysqli_connect_errno());
        // check for result 
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysqli_fetch_array($result);
            $salt = $result['salt'];
            $encrypted_password = $result['password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password
            if ($encrypted_password == $hash) {
                return $result;
            }
        } else {
            return false;
        }
    }






    /**
     * Check user is existed or not
     */
    /*public function isUserExisted($email) {
        $result = mysqli_query($this->db->con,"SELECT user_email from users WHERE user_email = '$email'");
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            // user exist
            return true;
        } else {
            // user not exist
            return false;
        }
    }*/








    /**
     * Get patient by user
     */
    public function getPatientByUsername($username) {
        //$result = mysqli_query($this->db->con,"SELECT * FROM screen WHERE create_by = '$username'") or die(mysqli_connect_errno());
        
        $user = mysqli_query($this->db->con,"SELECT * FROM users WHERE username='$username' ") or die(mysqli_connect_errno());
        
        while ($row = mysqli_fetch_array($user)) {
            $chwpart = $row['chwpart'];
            $amppart = $row['amppart'];
            $workat = $row['workat'];
            $status = $row['status'];
        }
        
        if( $status == 3 ){
            if( $workat == '0' ){
                //โรงพยาบาลส่งเสริมสุขภาพตำบล
                $result = mysqli_query($this->db->con,"SELECT * FROM screen WHERE create_by = '$username'") or die(mysqli_connect_errno());
            }else if( $workat == '1' || $workat == '2' ){
                //โรงพยาบาลชุมชน  || //โรงพยาบาลทั่วไป
                $result = mysqli_query($this->db->con,"SELECT * FROM screen WHERE create_by IN ( SELECT username FROM users WHERE chwpart='$chwpart' and amppart='$amppart' ) ") or die(mysqli_connect_errno());
            }else{
                //โรงพยาบาลศูนย์
                $result = mysqli_query($this->db->con,"SELECT * FROM screen WHERE create_by IN ( SELECT username FROM users WHERE chwpart='$chwpart' ) ") or die(mysqli_connect_errno());
            }
        }else{
            //admin & ผู้วิจัย
            $result = mysqli_query($this->db->con,"SELECT * FROM screen") or die(mysqli_connect_errno());
        }
        
        // check for result 
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            return $result;
        } else {
            return false;
        }
    }
    
    /**
     * Get DetailPatient-Screen by user,cid
     */
    public function getDetailPatient($username, $cid, $regdate) {
        $sql  = " SELECT s.*, p.PROVINCE_NAME, a.AMPHUR_NAME, d.DISTRICT_NAME FROM screen s";
        $sql .= " LEFT JOIN province p ON p.PROVINCE_ID = s.chwpart";
        $sql .= " LEFT JOIN amphur a ON a.AMPHUR_ID = s.amppart";
        $sql .= " LEFT JOIN district d ON d.DISTRICT_ID = s.tmbpart";
        $sql .= " WHERE s.cid='$cid' AND s.regdate='$regdate' ";

        $result = mysqli_query($this->db->con, $sql) or die(mysqli_connect_errno());
        // check for result 
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            return $result;
        } else {
            return false;
        }
    }
    
    
    
    
    
     /**
     * Get patient by Search
     */
    public function getPatientBySearch($username, $txtsearch) {
        //$result = mysqli_query($this->db->con,"SELECT * FROM screen WHERE create_by = '$username'") or die(mysqli_connect_errno());
        
        $user = mysqli_query($this->db->con,"SELECT * FROM users WHERE username='$username' ") or die(mysqli_connect_errno());
        
        while ($row = mysqli_fetch_array($user)) {
            $chwpart = $row['chwpart'];
            $amppart = $row['amppart'];
            $workat = $row['workat'];
            $status = $row['status'];
        }
        
        if( $status == 3 ){
            if( $workat == '0' ){
                //โรงพยาบาลส่งเสริมสุขภาพตำบล
                $result = mysqli_query($this->db->con,"SELECT * FROM screen WHERE ((fullname LIKE '%$txtsearch%') || (cid LIKE '%$txtsearch%')) AND create_by = '$username'") or die(mysqli_connect_errno());
            }else if( $workat == '1' || $workat == '2' ){
                //โรงพยาบาลชุมชน  || //โรงพยาบาลทั่วไป
                $result = mysqli_query($this->db->con,"SELECT * FROM screen WHERE ((fullname LIKE '%$txtsearch%') || (cid LIKE '%$txtsearch%')) AND create_by IN ( SELECT username FROM users WHERE chwpart='$chwpart' and amppart='amppart' ) ") or die(mysqli_connect_errno());
            }else{
                //โรงพยาบาลศูนย์
                $result = mysqli_query($this->db->con,"SELECT * FROM screen WHERE ((fullname LIKE '%$txtsearch%') || (cid LIKE '%$txtsearch%')) AND create_by IN ( SELECT username FROM users WHERE chwpart='$chwpart' ) ") or die(mysqli_connect_errno());
            }
        }else{
            //admin & ผู้วิจัย
            $result = mysqli_query($this->db->con,"SELECT * FROM screen WHERE ((fullname LIKE '%$txtsearch%') || (cid LIKE '%$txtsearch%')) ") or die(mysqli_connect_errno());
        }
        
        // check for result 
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            return $result;
        } else {
            return false;
        }
    }
    
    
    
    
    
    
    /**
     * Get Profile by username
     */
    public function getDetailProfile($username) {
        $result = mysqli_query($this->db->con,"SELECT * FROM users WHERE username='$username' ") or die(mysqli_connect_errno());
        // check for result 
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            return $result;
        } else {
            return false;
        }
    }
    
    
    
    
     /**
     * Update Profile
     */
    public function updateProfile($username, $name, $password, $address) {
        
        if( $password == '' ){
            $result = mysqli_query($this->db->con,"UPDATE users SET name='$name', address='$address' WHERE username='$username' ") or die(mysqli_error($this->db));
        }else{
            $hash = $this->hashSSHA($password);
            $encrypted_password = $hash["encrypted"]; // encrypted password
            $salt = $hash["salt"]; // salt
                    
            $result = mysqli_query($this->db->con,"UPDATE users SET name='$name', password='$encrypted_password', salt='$salt', address='$address' WHERE username='$username' ") or die(mysqli_error($this->db));
        }   
                       
        // check for result
        if ($result) {                
           return true;
        } else {
            return false;
        }
    }




     /**
     * get chwpart จังหวัด
     */
    public function getChwpart(){            
        // Mysql select query  
        $result = mysqli_query($this->db->con,"SELECT PROVINCE_ID, PROVINCE_NAME FROM province") or die(mysqli_connect_errno()); 

        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            return $result;
        } else {
            return false;
        }      
    }
    
     /**
     * get amppart อำเภอ
     */
    public function getAmppart($chwpart){   
        $result_chwid = mysqli_query($this->db->con,"SELECT PROVINCE_ID FROM province WHERE PROVINCE_NAME = '$chwpart'") or die(mysqli_connect_errno());
        $chwid = mysqli_fetch_array($result_chwid);

        // Mysql select query
        $result = mysqli_query($this->db->con,"SELECT AMPHUR_ID, AMPHUR_NAME FROM amphur where PROVINCE_ID = ".$chwid['PROVINCE_ID']) or die(mysqli_connect_errno());    

        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            return $result;
        } else {
            return false;
        }            
    }
    
     /**
     * get tmbpart ตำบล
     */
    public function getTmbpart($chwpart, $amppart){  
        $result_chwid = mysqli_query($this->db->con,"SELECT PROVINCE_ID FROM province WHERE PROVINCE_NAME = '$chwpart'") or die(mysqli_connect_errno());
        $chwid = mysqli_fetch_array($result_chwid);

        $result_ampid = mysqli_query($this->db->con,"SELECT AMPHUR_ID FROM amphur WHERE PROVINCE_ID = ".$chwid['PROVINCE_ID']." and AMPHUR_NAME = '$amppart' ") or die(mysqli_connect_errno());
        $ampid = mysqli_fetch_array($result_ampid);

        // Mysql select query
        $result = mysqli_query($this->db->con,"SELECT DISTRICT_ID, DISTRICT_NAME FROM district where PROVINCE_ID = ".$chwid['PROVINCE_ID']. " and AMPHUR_ID = ".$ampid['AMPHUR_ID']) or die(mysqli_connect_errno());  

        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            return $result;
        } else {
            return false;
        }           
    }



    /**
     * get id จังหวัด
     */
     public function getIDChw($chwpart)
     {
         $result_chwid = mysqli_query($this->db->con,"SELECT PROVINCE_ID FROM province WHERE PROVINCE_NAME = '$chwpart'") or die(mysqli_connect_errno());
         $chwid = mysqli_fetch_array($result_chwid);

         return $chwid['PROVINCE_ID'];
     }

     /**
     * get id อำเภอ
     */
     public function getIDAmp($chwpart, $amppart)
     {
         $result_ampid = mysqli_query($this->db->con,"SELECT AMPHUR_ID FROM amphur WHERE PROVINCE_ID = ".$chwpart." and AMPHUR_NAME = '$amppart' ") or die(mysqli_connect_errno());
         $ampid = mysqli_fetch_array($result_ampid);

         return $ampid['AMPHUR_ID'];
     }

     /**
     * get id ตำบล
     */
     public function getIDTmb($chwpart, $amppart, $tmbpart)
     {
         $result_tmbid = mysqli_query($this->db->con,"SELECT DISTRICT_ID FROM district where PROVINCE_ID = ".$chwpart. " and AMPHUR_ID = ".$amppart." and DISTRICT_NAME = '$tmbpart' ") or die(mysqli_connect_errno());  
         $tmbid = mysqli_fetch_array($result_tmbid);

         return $tmbid['DISTRICT_ID'];
     }





    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {

        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }






    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {

        $hash = base64_encode(sha1($password . $salt, true) . $salt);

        return $hash;
    }

}

?>
