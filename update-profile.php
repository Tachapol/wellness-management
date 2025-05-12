<?php
session_start();
include_once('php/connect.php');
echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
echo '<link href="https://fonts.googleapis.com/css2?family=Jacques+Francois&display=swap" rel="stylesheet">';
echo '<link rel="stylesheet" href="assets/css/style2.css">';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $city = $conn->real_escape_string($_POST['city']);
    $province = $conn->real_escape_string($_POST['province']);
    $postal_code = $conn->real_escape_string($_POST['postal_code']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $date_of_birth = $conn->real_escape_string($_POST['date_of_birth']);

    $query = "UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ?, city = ?, province = ?, postal_code = ?, gender = ?, date_of_birth = ?, updated_at = NOW() WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssssi", $first_name, $last_name, $email, $phone, $address, $city, $province, $postal_code, $gender, $date_of_birth, $user_id);

    if ($stmt->execute()) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Profile Updated',
                    text: 'Your profile has been updated successfully!',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'profile.php';
                });
            });
        </script>";
    } else {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: 'There was an error updating your profile. Please try again later.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.history.back();
                });
            });
        </script>";
    }
    $stmt->close();
    $conn->close();
}
?>