<?php
    session_start();
    include('connect.php');

    $errors = array();

    if(isset($_POST['login_user'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        if(count($errors)== 0) {
            $query = "SELECT * FROM user WHERE email = '$email' AND password = '$password' ";
            $result = mysqli_query($conn, $query);

            if(mysqli_num_rows($result) == 1) {
                $_SESSION['email'] = $email;
                $_SESSION['sucess'] = "You are now logged in";
                header("location: ../index.php");
                
            } else {
                array_push($erros, "Incorrect email or password.");
                $_SESSION['error'] = "Incorrect email or password."; 
                header("location: ../login.php");
            }
        }
    } 

?>