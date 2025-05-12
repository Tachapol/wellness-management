<?php
    error_reporting(E_ALL);
    $conn = new mysqli('localhost','root','','wellness_db');
    // $conn = new mysqli('localhost','wannasle_db','W4nS-u90uJ-iE5','wannasle_db');
    $conn->set_charset('utf8');
    if ($conn->connect_errno){
        echo "Connected Error : ".$conn->connect_error;
        exit();
    }
    $base_path_blog = 'assets/images/condo/';
    // Timezone
    date_default_timezone_set('Asia/Bangkok');

?>

