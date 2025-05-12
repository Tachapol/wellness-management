<?php require_once('connect.php'); ?>
<?php
    if(isset($_POST['submit_reset'])){
        $sql = "UPDATE `user` SET `password` WHERE `email` = '".$_POST['password']."' ";
        $result = $conn->query($sql);
        if($result){
            $_SESSION['password'] = $password;
            $_SESSION['sucess'] = "Password Changed!";
            // header("location: ../index.php");
        } else {
            // echo '<script> alert("Changed Error.")</script>'; 
            // header('Refresh:0; url=index.php');
        }

    } else {
        // header('Refresh:0; url=index.php');
    }
?>