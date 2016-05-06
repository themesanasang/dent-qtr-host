<?php
error_reporting(E_ALL ^ E_DEPRECATED);
class db_functions {

    private $db;

    // constructor
    function __construct() {
        require_once 'DB_Connect.php';
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
    public function storeScreen($create_by, $cid, $fullname, $age, $address, $pic_logo, $pic_1, $pic_2, $pic_3, $regdate) {
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');
        
        $result = mysqli_query($this->db->con,"INSERT INTO screen(cid, fullname, age, address, pic_logo, pic_1, pic_2, pic_3, regdate, create_by, updated_at, created_at) VALUES('$cid', '$fullname', '$age', '$address', '$pic_logo', '$pic_1', '$pic_2', '$pic_3', '$regdate', '$create_by', '$updated_at', '$created_at')") or die(mysqli_error($this->db));
        // check for result
        if ($result) {
           return true;
        } else {
            return false;
        }
    }
    
    
    
    
    
    /**
     * Update Screen
     */
    public function updateScreen($id_edit, $cid, $fullname, $age, $address, $pic_logo, $pic_1, $pic_2, $pic_3) {
        $updated_at = date('Y-m-d H:i:s');
        
        $result = mysqli_query($this->db->con,"UPDATE screen SET cid='$cid', fullname='$fullname', age='$age', address='$address', updated_at='$updated_at' WHERE id=$id_edit ") or die(mysqli_error($this->db));
                       
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
                $result = mysqli_query($this->db->con,"SELECT * FROM screen WHERE create_by IN ( SELECT username FROM users WHERE chwpart='$chwpart' and amppart='amppart' ) ") or die(mysqli_connect_errno());
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
        $result = mysqli_query($this->db->con,"SELECT * FROM screen WHERE cid='$cid' AND regdate='$regdate' ") or die(mysqli_connect_errno());
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
