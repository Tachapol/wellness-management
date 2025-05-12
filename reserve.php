<?php
// Display errors
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();
include_once('php/connect.php');

// Check database connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: form-login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);

// Check if all required POST data is provided
if (!isset($_POST['promotion_id'], $_POST['date'], $_POST['time'], $_POST['notes'])) {
    die("Error: Missing required fields.");
}

// Retrieve and sanitize form data
$promotion_id = intval($_POST['promotion_id']);
$date = $conn->real_escape_string($_POST['date']);
$time = $conn->real_escape_string($_POST['time']);
$notes = htmlspecialchars($conn->real_escape_string($_POST['notes']), ENT_QUOTES, 'UTF-8');

// Validate date and time format
$date_valid = DateTime::createFromFormat('Y-m-d', $date);
$time_valid = DateTime::createFromFormat('H:i', $time);

if (!$date_valid || !$time_valid) {
    showAlertAndRedirect('error', 'Invalid Input', 'Date or time format is incorrect.', 'profile.php');
}

// Check if the reservation date is in the future
if (strtotime($date) < strtotime(date('Y-m-d'))) {
    showAlertAndRedirect('error', 'Invalid Date', 'Reservation date must be in the future.', 'profile.php');
}

// Check if the promotion exists
$promotion_check_query = "SELECT id FROM promotion WHERE id = ?";
$promotion_stmt = $conn->prepare($promotion_check_query);
$promotion_stmt->bind_param("i", $promotion_id);
$promotion_stmt->execute();
$promotion_result = $promotion_stmt->get_result();

if ($promotion_result->num_rows === 0) {
    $promotion_stmt->close();
    showAlertAndRedirect('error', 'Invalid Promotion', 'Selected promotion does not exist.', 'profile.php');
}
$promotion_stmt->close();

// Check if the time slot is already reserved
$check_query = "SELECT id FROM reservations WHERE date = ? AND time = ?";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param("ss", $date, $time);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    $check_stmt->close();
    showAlertAndRedirect('error', 'Time Slot Unavailable', 'The selected time slot is already reserved. Please choose a different time.', 'profile.php');
}
$check_stmt->close();

// Add reservation
$query = "INSERT INTO reservations (user_id, promotion_id, date, time, notes) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);

if (!$stmt) {
    error_log("SQL Error (prepare): " . $conn->error);
    showAlertAndRedirect('error', 'Server Error', 'Unable to process your reservation.', 'profile.php');
}

$stmt->bind_param("iisss", $user_id, $promotion_id, $date, $time, $notes);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    showAlertAndRedirect('success', 'Reservation Successful', 'Your reservation has been successfully created.', 'profile.php');
} else {
    error_log("SQL Error (execute): " . $stmt->error);
    $stmt->close();
    $conn->close();
    showAlertAndRedirect('error', 'Server Error', 'Unable to complete reservation.', 'profile.php');
}

// Function to display SweetAlert and redirect

function showAlertAndRedirect($icon, $title, $message, $redirect) {
    $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    echo "<!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <link rel='stylesheet' href='assets/css/style2.css'>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: '$icon',
                title: '$title',
                text: '$message',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'custom-swal-popup',
                    title: 'custom-swal-title',
                    confirmButton: 'custom-swal-cancel-button'
                }
            }).then(function() {
                window.location.href = '$redirect';
            });
        </script>
    </body>
    </html>";
    exit();
}