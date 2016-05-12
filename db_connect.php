<?php
error_reporting(E_ALL ^ E_DEPRECATED);
class db_connect
{
    public $con;
    // constructor
    function __construct()
    {
    }
    // destructor
    function __destruct()
    {
        // $this->close();
    }
    // Connecting to database
    public function connect()
    {
        require_once 'config/db_config.php';
        // connecting to mysql
        $this->con = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_error($this->con));
        if (mysqli_connect_errno()) {
            die("Database connection failed");
        }
        mysqli_set_charset($this->con,"utf8");
        // return database handler
        return $this->con;
    }
    // Closing database connection
    public function close()
    {
        mysqli_close($this->con);
    }
}
?>
