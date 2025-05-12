<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('php/connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $email = $conn->real_escape_string($_POST['email']);
    $password_raw = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

    // Phone validation
    if (!preg_match('/^\d{10}$/', $phone)) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Phone Number',
                    text: 'Phone number must be exactly 10 digits.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'form-signup.php';
                });
            });
        </script>";
        exit();
    }

    // Validate confirm password
    if ($password_raw !== $confirm_password) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch',
                    text: 'Password and Confirm Password do not match.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'form-signup.php';
                });
            });
        </script>";
        exit();
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Email',
                    text: 'Please enter a valid email address.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'form-signup.php';
                });
            });
        </script>";
        exit();
    }

    // Validate password length
    if (strlen($password_raw) < 6) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Weak Password',
                    text: 'Password must be at least 6 characters long.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'form-signup.php';
                });
            });
        </script>";
        exit();
    }

    // Hash the password
    $password = password_hash($password_raw, PASSWORD_DEFAULT);

    // Check if the email already exists
    $check_email_query = "SELECT email FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Email Already Registered',
                    text: 'The email address is already registered. Please use a different email.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'form-signup.php';
                });
            });
        </script>";
        $stmt->close();
        $conn->close();
        exit();
    }
    $stmt->close();

    // Check if the phone number already exists
    $check_phone_query = "SELECT phone FROM users WHERE phone = ?";
    $stmt = $conn->prepare($check_phone_query);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Phone Number Already Registered',
                    text: 'The phone number is already registered. Please use a different phone number.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'form-signup.php';
                });
            });
        </script>";
        $stmt->close();
        $conn->close();
        exit();
    }
    $stmt->close();

    // Insert user data
    $insert_query = "INSERT INTO users (first_name, last_name, phone, email, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssss", $first_name, $last_name, $phone, $email, $password);

    if ($stmt->execute()) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Sign Up Successful',
                    text: 'Your account has been created successfully!',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'form-login.php';
                });
            });
        </script>";
    } else {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Registration Failed',
                    text: 'There was an error registering your account. Please try again later.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'form-signup.php';
                });
            });
        </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
