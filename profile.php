<?php
// Start session only if it hasn't been started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once('php/connect.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$query = "SELECT user_id, first_name, last_name, email, phone, date_of_birth, gender, address, city, province, postal_code, registration_date, last_login, status FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

// Fetch upcoming reservations
$query_upcoming = "
    SELECT 
        r.date AS reservation_date,
        r.time AS reservation_time,
        r.status AS reservation_status,
        p.name_en AS program_name,
        p.details_en AS program_details,
        p.price AS program_price,
        p.price_discount AS program_discount
    FROM 
        reservations r
    INNER JOIN 
        promotion p
    ON 
        r.promotion_id = p.id
    WHERE 
        r.user_id = ? AND r.date >= CURDATE()
    ORDER BY 
        r.date ASC, r.time ASC";
$stmt_upcoming = $conn->prepare($query_upcoming);
if (!$stmt_upcoming) {
    die("Prepare failed: " . $conn->error);
}
$stmt_upcoming->bind_param("i", $user_id);
$stmt_upcoming->execute();
$result_upcoming = $stmt_upcoming->get_result();
$upcoming_reservations = $result_upcoming->fetch_all(MYSQLI_ASSOC);

// Fetch history reservations
$query_history = "
    SELECT 
        r.date AS reservation_date,
        r.time AS reservation_time,
        r.status AS reservation_status,
        p.name_en AS program_name,
        p.details_en AS program_details,
        p.price AS program_price,
        p.price_discount AS program_discount
    FROM 
        reservations r
    INNER JOIN 
        promotion p
    ON 
        r.promotion_id = p.id
    WHERE 
        r.user_id = ? AND r.date < CURDATE()
    ORDER BY 
        r.date DESC, r.time DESC";
$stmt_history = $conn->prepare($query_history);
if (!$stmt_history) {
    die("Prepare failed: " . $conn->error);
}
$stmt_history->bind_param("i", $user_id);
$stmt_history->execute();
$result_history = $stmt_history->get_result();
$history_reservations = $result_history->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
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
</head>

<body>
    <!-- Section Navbar -->
    <header class="header">
        <?php include_once('includes/navbar-wellness.php')?>
    </header>

       <!-- My Programs -->
       <section class="my-programs-section">
        <div class="container mt-5">
            <hr>
            <h1 class="mb-2">My Programs</h1>

            <!-- Upcoming Programs -->
            <h3 class="toggle-header" onclick="toggleSection('upcoming-programs')">Upcoming Programs</h3>
                <div id="upcoming-programs" class="toggle-content">
                    <?php if (!empty($upcoming_reservations)): ?>
                        <ul class="list-group">
                        <?php foreach ($upcoming_reservations as $reservation): ?>
                            <li class="list-group-item program-list">
                                <strong><i class="fas fa-spa"></i> <?php echo htmlspecialchars($reservation['program_name']); ?></strong><br>
                                <?php echo htmlspecialchars($reservation['program_details']); ?><br>

                                <i class="fas fa-calendar-alt"></i>
                                <?php echo date("d M Y", strtotime($reservation['reservation_date'])); ?><br>

                                <i class="fas fa-clock"></i>
                                <?php echo date("h:i A", strtotime($reservation['reservation_time'])); ?><br>

                                <i class="fas fa-dollar-sign"></i>
                                Price:
                                <?php 
                                    $price = $reservation['program_discount'] > 0 
                                        ? $reservation['program_discount'] 
                                        : $reservation['program_price'];
                                    echo number_format($price);
                                ?><br>

                                <i class="fas fa-info-circle"></i>
                                Status: <?php echo htmlspecialchars($reservation['reservation_status']); ?>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>No upcoming programs.</p>
                    <?php endif; ?>
                </div>

            <!-- History Programs -->
            <h3 class="toggle-header" onclick="toggleSection('history-programs')">History Programs</h3>
            <div id="history-programs" class="toggle-content">
                <?php if (!empty($history_reservations)): ?>
                    <ul class="list-group">
                    <?php foreach ($history_reservations as $reservation): ?>
                        <li class="list-group-item program-list">
                            <strong><i class="fas fa-spa"></i> <?php echo htmlspecialchars($reservation['program_name']); ?></strong><br>

                            <i class="fas fa-calendar-alt"></i>
                            <?php echo date("d M Y", strtotime($reservation['reservation_date'])); ?><br>

                            <i class="fas fa-clock"></i>
                            <?php echo date("h:i A", strtotime($reservation['reservation_time'])); ?><br>

                            <i class="fas fa-dollar-sign"></i>
                            Price:
                            <?php 
                                $price = $reservation['program_discount'] > 0 
                                    ? $reservation['program_discount'] 
                                    : $reservation['program_price'];
                                echo number_format($price);
                            ?><br>

                            <i class="fas fa-info-circle"></i>
                            Status: 
                            <?php 
                                $status = htmlspecialchars($reservation['reservation_status']);
                                if ($status === 'confirmed') {
                                    echo '<span style="color: green;"><i class="fas fa-check-circle"></i> Confirmed</span>';
                                } elseif ($status === 'pending') {
                                    echo '<span style="color: orange;"><i class="fas fa-hourglass-half"></i> Pending</span>';
                                } elseif ($status === 'cancelled') {
                                    echo '<span style="color: red;"><i class="fas fa-times-circle"></i> Cancelled</span>';
                                } else {
                                    echo $status;
                                }
                            ?>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No history programs.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Profile -->
    <section class="profile-section">
        <h2>Edit Profile</h2>
        <div class="container mt-5 profile-container">
            <form action="update-profile.php" method="POST">
                <div class="row mx-5">
                    <!-- First Name -->
                    <div class="input-box col-md-6">
                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id']); ?>">
                        <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                        <label>First Name</label>
                    </div>
                        
                    <!-- Last Name -->
                    <div class="input-box col-md-6">
                        <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                        <label>Last Name</label>
                    </div>

                    <!-- Email -->
                    <div class="input-box">
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        <label>Email</label>
                    </div>

                    <!-- Phone -->
                    <div class="input-box">
                        <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                        <label>Phone</label>
                    </div>

                    <!-- Address -->
                    <div class="input-box">
                        <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
                        <label>Address</label>
                    </div>

                    <!-- City -->
                    <div class="input-box">
                        <input type="text" name="city" value="<?php echo htmlspecialchars($user['city']); ?>">
                        <label>City</label>
                    </div>

                    <!-- Province -->
                    <div class="input-box">
                        <input type="text" name="province" value="<?php echo htmlspecialchars($user['province']); ?>">
                        <label>Province</label>
                    </div>

                    <!-- Postal Code -->
                    <div class="input-box">
                        <input type="text" name="postal_code" value="<?php echo htmlspecialchars($user['postal_code']); ?>">
                        <label>Postal Code</label>
                    </div>

                    <!-- Gender -->
                    <div class="input-box">
                        <select name="gender" required>
                            <option value="" disabled>Select Gender</option>
                            <option value="male" <?php echo $user['gender'] === 'male' ? 'selected' : ''; ?>>Male</option>
                            <option value="female" <?php echo $user['gender'] === 'female' ? 'selected' : ''; ?>>Female</option>
                            <option value="other" <?php echo $user['gender'] === 'other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>

                    <!-- Date of Birth -->
                    <div class="input-box">
                        <input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($user['date_of_birth']); ?>" required>
                        <label>Date of Birth</label>
                    </div>

                <button type="submit" class="btn-update">Update Profile</button>
            </form>
        </div>
    </section>

    <script>
        function toggleSection(sectionId) {
            const section = document.getElementById(sectionId);
            if (section.style.display === "none" || section.style.display === "") {
                section.style.display = "block";
            } else {
                section.style.display = "none";
            }
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const upcomingPrograms = document.getElementById("upcoming-programs");
            if (upcomingPrograms) {
                upcomingPrograms.style.display = "block"; // Ensure it's visible
            }
        });
    </script>

</body>
</html>