<?php
require_once('php/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $line = $_POST['line'];
    $detail = $_POST['detail'];
    $created_at = date('Y-m-d H:i:s');

    if (empty($name) || empty($phone) || empty($email)) {
        header("Location: contact.php?status=error&message=Name, phone, and email are required!");
        exit();
    }

    $query = "INSERT INTO contacts (name, phone, email, line, detail, created_at) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $name, $phone, $email, $line, $detail, $created_at);

    if ($stmt->execute()) {
        header("Location: contact.php?status=success&message=Your contact information has been submitted successfully.");
    } else {
        header("Location: contact.php?status=error&message=Something went wrong. Please try again later.");
    }

    $stmt->close();
    $conn->close();
}
?>