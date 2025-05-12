<?php require_once('connect.php') ?>
<?php
    session_start();
    if(isset($_POST['submit'])){
        $sql_check_email = "SELECT * FROM `user` WHERE `email` = '".$_POST['email']."' ";
        $check_email =  $conn->query($sql_check_email) or die($conn->error);
        if(!$check_email->num_rows){
            // hash password
            // $hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $sql = "INSERT INTO `user` (`first_name`, `last_name`, `email`, `password`)
                        VALUES ('".$_POST['first_name']."',
                                 '".$_POST['last_name']."',
                                 '".$_POST['email']."',
                                 '".$_POST['password']."');";
            $result = $conn->query($sql);
            if($result){
                $_SESSION['sucess'] = "Welcome to Creamily Vang"; 
                header('Refresh:0; url=../index.php');
            } else {
                // echo '<script> alert("Creating Error!")</script>'; 
                // header('Refresh:0; url=index.php');
                // print_r($result);
                // echo $sql;
            }
            
        } else {
            // array_push($errors,  "Email is already exits."); 
            $_SESSION['error'] = "";

            // echo '<script> alert("Email  is already exists!")</script>'; 
            header('Refresh:0; url=../register.php');
        }

    } else {
        header('Refresh:0; url=index.php');
    }
?>


<!-- l 

    Welcome to GemondoTh!
    You've activated your customer account. Next time you shop with us, log in for faster checkout.

    This email address is already associated with an account. If this account is yours, you can <a>reset your password<a>





l -->