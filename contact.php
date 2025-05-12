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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

      <!-- Section Navbar -->
      <header class="header">
        <?php include_once('includes/navbar-wellness.php')?>
    </header>

    <!-- contact -->
    <section class="contact-form-container">
        <h1>Contact Us</h1>
        <div class="container">
                <form action="submit-contact.php" method="POST">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="text" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="line">Line ID:</label>
                        <input type="text" id="line" name="line">
                    </div>
                    <div class="form-group">
                        <label for="detail">Details:</label>
                        <textarea id="detail" name="detail" rows="5"></textarea>
                    </div>
                    <button type="submit" class="btn-submit">Submit</button>
                </form>
        </div>
    </section>

    <?php if (isset($_GET['status']) && isset($_GET['message'])): ?>
    <script>
    Swal.fire({
        icon: '<?php echo $_GET['status'] === 'success' ? 'success' : 'error'; ?>',
        title: '<?php echo $_GET['status'] === 'success' ? 'Thank you!' : 'Oops...'; ?>',
        text: '<?php echo htmlspecialchars($_GET['message']); ?>',
        customClass: {
            popup: 'custom-swal-popup',
            title: 'custom-swal-title',
            confirmButton: 'custom-swal-confirm-button',
            cancelButton: 'custom-swal-cancel-button'
        }
    });
    </script>
    <?php endif; ?>
</body>
</html>

