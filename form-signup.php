<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (file_exists('php/connect.php')) {
    include_once('php/connect.php');
} else {
    die('Error: The required file "php/connect.php" does not exist.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Password Mismatch',
                text: 'Password and Confirm Password do not match.'
            }).then(() => {
                window.history.back();
            });
        </script>";
        exit();
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Arura</title>

    <!-- CSS -->
    <link rel="stylesheet" href="node_modules\bootstrap\dist\css\bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jacques+Francois&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style2.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!-- Section Navbar -->
    <header class="header">
        <?php include_once('includes/navbar-wellness.php')?>
    </header>

    <!-- Section Sign Up -->
    <section>
        <div class="container">
            <div class="wrapper">
                <div class="form-box signUp">
                    <h2>Sign Up</h2>
                    <form action="signup.php" method="POST">
                        <div class="input-box">
                            <input type="text" name="first_name" required>
                            <label>First Name</label>
                        </div>
                        <div class="input-box">
                            <input type="text" name="last_name" required>
                            <label>Last Name</label>
                        </div>
                        <div class="input-box">
                            <input type="tel" name="phone" maxlength="10" required>
                            <label>Phone Number</label>
                        </div>
                        <div class="input-box">
                            <input type="text" name="email" required>
                            <label>Email</label>
                        </div>
                        <div class="input-box-password">
                            <input type="password" id="password" name="password" required>
                            <label for="password">Password</label>
                            <div class="toggle-password" onclick="togglePasswordVisibility('password')"></div>
                        </div>
                        <div class="input-box-password">
                            <input type="password" id="confirm_password" name="confirm_password" required>
                            <label for="confirm_password">Confirm Password</label>
                            <div class="toggle-password" onclick="togglePasswordVisibility('confirm_password')"></div>
                        </div>
                        <div class="button-container">
                            <button class="btn-signUp">SIGN UP</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="assets/js/main.js" defer></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const inputs = document.querySelectorAll("input, select");

        inputs.forEach((input, index) => {
            input.addEventListener("keydown", function (event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    let nextInput = inputs[index + 1];
                    while (nextInput && (nextInput.tagName !== "INPUT" && nextInput.tagName !== "SELECT")) {
                        nextInput = inputs[++index];
                    }
                    if (nextInput) {
                        nextInput.focus();
                    }
                }
            });
        });
    });

    // Number input validation
    document.addEventListener("DOMContentLoaded", function () {
        const numberInputs = document.querySelectorAll("input[type='number'], input[type='tel']");

        numberInputs.forEach((input) => {
            // Prevent invalid characters from being entered
            input.addEventListener("keydown", function (event) {
                // Allow: Backspace, Delete, Tab, Escape, Enter, Arrow keys
                if (
                    event.key === "Backspace" ||
                    event.key === "Delete" ||
                    event.key === "Tab" ||
                    event.key === "Escape" ||
                    event.key === "Enter" ||
                    (event.key >= "ArrowLeft" && event.key <= "ArrowDown")
                ) {
                    return; // Allow these keys
                }

                // Allow: Numbers (0-9) and Numpad numbers
                if ((event.key >= "0" && event.key <= "9") || (event.key >= "Numpad0" && event.key <= "Numpad9")) {
                    return; // Allow numeric keys
                }

                // Prevent all other keys
                event.preventDefault();
            });
        });
    });

    document.querySelector("form").addEventListener("submit", function (event) {
        const email = document.querySelector("input[name='email']");
        const password = document.querySelector("input[name='password']");
        const confirmPassword = document.querySelector("input[name='confirm_password']");
        const phone = document.querySelector("input[name='phone']");

        // Phone validation
        if (phone.value.length !== 10 || !/^\d{10}$/.test(phone.value)) {
            Swal.fire({
                icon: "error",
                title: "Invalid Phone Number",
                text: "Phone number must be exactly 10 digits.",
            });
            event.preventDefault();
            return;
        }

        // Email validation
        if (!email.value.includes("@")) {
            Swal.fire({
                icon: "error",
                title: "Invalid Email",
                text: "Please enter a valid email address.",
            });
            event.preventDefault();
            return;
        }

        // Password validation
        const passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;

        if (!passwordRegex.test(password.value)) {
            Swal.fire({
                icon: "error",
                title: "Weak Password",
                text: "Password must be at least 6 characters long and include at least one uppercase letter, one number, and one special character.",
            });
            event.preventDefault();
            return;
        }

        // Confirm password validation
        if (password.value !== confirmPassword.value) {
            Swal.fire({
                icon: "error",
                title: "Password Mismatch",
                text: "Password and Confirm Password do not match.",
            });
            event.preventDefault();
            return;
        }
    });
        
    // Password visibility toggle
    function togglePasswordVisibility(fieldId) {
        const passwordInput = document.getElementById(fieldId);
        const toggleButton = passwordInput.nextElementSibling;

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleButton.classList.add("show");
        } else {
            passwordInput.type = "password";
            toggleButton.classList.remove("show");
        }
    }
    </script>
</body>
</html>