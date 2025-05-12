<?php 
    require_once('php/connect.php');
    $tag = isset($_GET['tag']) ? $_GET['tag'] : 'all';
    $sql = "SELECT * FROM `promotion` WHERE `tag` LIKE '%".$tag."%' AND `status` = 'true' ORDER BY RAND() LIMIT 12";
    $result = $conn->query($sql);
    if (!$result){
        header('Location: index.php');
    }
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

    <!-- Section About -->
    <section class="about" id="about">
        <div class="content">
            <h2>Active Aura</h2>
            <h3>Wellness Center</h3>
            <hr class="divider" />
            <p>Welcome to <span class="highlight">Active Aura Wellness Center</span>, where we prioritize your health and well-being. 
                Our center is designed to offer a holistic approach to wellness, focusing on physical, mental, 
                and emotional balance. With an array of services tailored to your unique needs, we strive to 
                create a nurturing environment that promotes relaxation, healing, and vitality.
            </p>
            <a href="#package" class="btn">More info&nbsp;<span class="fa-solid fa-arrow-right" id="arrow2" ></span></a>
        </div>

        <div class="image">
            <img src="assets/images/wellness/about.jpeg" alt="">
        </div>  
    </section>

    

    <!-- Section Package -->
    <section class="package p-5" id="package">
        <div class="content">
            <h1 class="text-title text-center py-5">Packages</h1>
            <div class="container py-5 px-5">
                <div class="row">
                    <div class="package-img-left col-lg-6">
                        <img src="assets/images/wellness/pro_package1.jpeg" alt="">
                    </div>
                    <p class="text-content-left col-lg-6 px-0">At Active Aura Wellness Center, we offer a variety of packages to suit your individual 
                        wellness goals and needs. Whether you’re looking for relaxation, stress relief, fitness, 
                        or a complete rejuvenation experience, our tailored packages provide the best value and 
                        experience for your journey to well-being.
                    </p>
                </div>
                <div class="text-content-right col-lg-5 py-5 px-0">Each package is carefully crafted to ensure that you experience the ultimate in relaxation, 
                    rejuvenation, and overall well-being. Let us guide you through a trans formative experience
                        where your wellness goals are our top priority.
                </div>
                <div class="package-img-right col-lg-6">
                    <img src="assets/images/wellness/pro_packga2.jpeg" alt="">
                </div>
            </div>
        </div>
    </section>

    <!-- Section Our Package -->
    <section class="promotion mt-10" id="promotion">
        <div class="container">
            <h1 class="text-subtitle">Our Package</h1>
            <hr class="divider-left"/>
            <div class="row">
            <?php if ($result->num_rows > 0) { 
                while ($row = $result->fetch_assoc()) { 
                    $promotion = $row;
                    $imagePath = $base_path_blog . $row['image'];
                    if (!file_exists($imagePath)) {
                        $imagePath = 'assets/images/default.png'; // Use a default image
                    }
            ?>
            <div class="promotion-body col-lg-4 col-md-6 col-sm-12 p-5">
                <img src="<?php echo $imagePath; ?>" class="card-img" alt="Promotion Image">
                <div class="our-package-title">
                    <?php echo htmlspecialchars($promotion['name_en']); ?>
                </div>
                <div class="package-description">
                    <?php echo htmlspecialchars($promotion['details_en']); ?>
                    <div class="package-price mt-4 text-bold"><?php echo number_format($promotion['price']);?>฿</div>
                </div>
                <div class="reserve-promo-btn">
                    <d type="button" 
                        data-bs-toggle="modal" 
                        data-bs-target="#reserveModal"
                        data-promotion-id="<?php echo $promotion['id']; ?>"
                        data-promotion-name="<?php echo htmlspecialchars($promotion['name_en']); ?>"
                        data-promotion-details="<?php echo htmlspecialchars($promotion['details_en']); ?>"
                        data-promotion-price="<?php echo is_numeric($promotion['price']) ? number_format($promotion['price']) : '0'; ?>">
                        RESERVE
                    </d>
                </div>
            </div>
        <?php }
        } else { ?>
            <p class="text-center">No promotions available at the moment.</p>
        <?php } ?>
        </div>
    </div>
</section>

<!-- Reserve Modal -->
<div class="modal fade" id="reserveModal" tabindex="-1" aria-labelledby="reserveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reserveModalLabel">Reserve Your Package</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="package-details mb-3">
                    <h1 class="package-name"></h1>
                    <h4 class="package-description"></h4>
                    <h3><strong>Price:</strong> <span class="package-price"></span></h3>
                </div>
                <form action="reserve.php" method="POST">
                    <input type="hidden" name="promotion_id">
                    <div class="mb-3">
                        <label for="date" class="form-label">Reservation Date</label>
                        <input type="date" class="form-control" name="date" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="time" class="form-label">Reservation Time</label>
                        <input type="time" class="form-control" name="time" step="1800" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Additional Notes</label>
                        <textarea class="form-control" name="notes" rows="4" placeholder="Any special requests or notes"></textarea>
                    </div>
                    <button type="submit" class="btn-confirm">Confirm Reservation</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Footer -->
    <section>
        <?php include_once('includes/footer-wellness.php')?>
    </section>


    <script>
    // Get the modal and its elements
    const reserveModal = document.getElementById('reserveModal');
    const packageNameElement = reserveModal.querySelector('.package-name');
    const packageDescriptionElement = reserveModal.querySelector('.package-description');
    const packagePriceElement = reserveModal.querySelector('.package-price');
    const packageIdInput = reserveModal.querySelector('input[name="promotion_id"]');

    // Add an event listener to the modal
    reserveModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        const packageId = button.getAttribute('data-promotion-id');
        const packageName = button.getAttribute('data-promotion-name');
        const packageDescription = button.getAttribute('data-promotion-details');
        const packagePrice = button.getAttribute('data-promotion-price');

        packageNameElement.textContent = packageName;
        packageDescriptionElement.textContent = packageDescription;
        packagePriceElement.textContent = packagePrice ? packagePrice + ' THB' : 'Price not available';
        packageIdInput.value = packageId;
    });
</script>

</body>
</html>
<script src="assets/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>