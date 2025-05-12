<?php
session_start();
// Include SweetAlert2 for alerts
echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
include_once('php/connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['email'])) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Email Required',
                    text: 'Please enter your email address.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.history.back();
                });
            });
        </script>";
        exit();
    }

    $email = strtolower($conn->real_escape_string($_POST['email']));
    $password = $_POST['password'];

    // Fetch user data
    $query = "SELECT user_id, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: 'Failed to prepare the query. Please try again later.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.history.back();
                });
            });
        </script>";
        exit();
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'User Not Found',
                    text: 'No user found with the provided email.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.history.back();
                });
            });
        </script>";
        exit();
    }

    $user = $result->fetch_assoc();
    if ($user) {
        if (!password_verify($password, $user['password'])) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Password',
                        text: 'The password you entered is incorrect.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.history.back();
                    });
                });
            </script>";
            exit();
        }

        // Login successful
        $_SESSION['user_id'] = $user['user_id'];
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Login Successful',
                    text: 'Welcome back!',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'profile.php';
                });
            });
        </script>";
        exit();
    } else {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Login Failed',
                    text: 'An unexpected error occurred. Please try again later.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.history.back();
                });
            });
        </script>";
        exit();
    }
}
?>