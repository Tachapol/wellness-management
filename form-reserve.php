<?php
session_start();
include_once('php/connect.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: form-login.php");
    exit();
}

// Fetch package details if a package is selected
$package_id = isset($_GET['package_id']) ? intval($_GET['package_id']) : null;
$package = null;

if ($package_id) {
    $query = "SELECT * FROM packages WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $package_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $package = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve Package</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style2.css">
</head>
<body>
    <header>
        <?php include_once('includes/navbar-wellness.php'); ?>
    </header>

    <section class="reserve-section mt-10">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Reserve Your Package</h2>
                    <?php if ($package): ?>
                        <div class="package-details mb-3">
                            <h3><?php echo htmlspecialchars($package['name']); ?></h3>
                            <p><?php echo htmlspecialchars($package['description']); ?></p>
                            <p><strong>Price:</strong> <?php echo htmlspecialchars($package['price']); ?> THB</p>
                        </div>
                    <?php endif; ?>

                    <!-- Reserve Button -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reserveModal">
                        Reserve Now
                    </button>

                    <!-- Reserve Modal -->
                    <div class="modal fade" id="reserveModal" tabindex="-1" aria-labelledby="reserveModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="reserveModalLabel">Reserve Your Package</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="reserve.php" method="POST">
                                        <input type="hidden" name="package_id" value="<?php echo htmlspecialchars($package_id); ?>">
                                        <div class="mb-3">
                                            <label for="date" class="form-label">Reservation Date</label>
                                            <input type="date" class="form-control" name="date" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="time" class="form-label">Reservation Time</label>
                                            <input type="time" class="form-control" name="time" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="notes" class="form-label">Additional Notes</label>
                                            <textarea class="form-control" name="notes" rows="4"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Reserve</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Modal -->
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>