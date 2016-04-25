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
    public function storeScreen($create_by, $cid, $fullname, $address, $pic_logo, $pic_1, $pic_2, $pic_3, $regdate) {
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');
        
        $result = mysqli_query($this->db->con,"INSERT INTO screen(cid, fullname, address, pic_logo, pic_1, pic_2, pic_3, regdate, create_by, updated_at, created_at) VALUES('$cid', '$fullname', '$address', '$pic_logo', '$pic_1', '$pic_2', '$pic_3', '$regdate', '$create_by', '$updated_at', '$created_at')") or die(mysqli_error($this->db));
        // check for result
        if ($result) {
           return true;
        } else {
            return false;
        }
    }






    /**
     * Get user by email and password
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
        $result = mysqli_query($this->db->con,"SELECT * FROM screen WHERE create_by = '$username'") or die(mysqli_connect_errno());
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
        $result = mysqli_query($this->db->con,"SELECT * FROM screen WHERE create_by = '$username' AND cid='$cid' AND regdate='$regdate' ") or die(mysqli_connect_errno());
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
