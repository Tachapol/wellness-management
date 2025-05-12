<?php 
    require_once('php/connect.php');
    $tag = isset($_GET['tag']) ? $_GET['tag'] : 'promotion';
    $sql = "SELECT * FROM `promotion` WHERE `tag` LIKE '%".$tag."%' AND `status` = 'true' ORDER BY RAND() LIMIT 6";
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="assets/css/style2.css">
</head>

<body>

<!-- Section Navbar -->
<header class="header">
    <?php include_once('includes/navbar-wellness.php')?>
</header>

<!-- Section Promotion -->
<section class="promotion mt-10" id="promotion">
<div class="container">
    <h1>Promotion</h1>
    <h2 class="promotion-subtitle">All Promotion</h2>
    <p class="promotion-subtitle2">Select for the best wellness for you</p>
    <hr class="promotion-divider"/>
    
    <div class="row">
    <?php if ($result->num_rows > 0) { 
        while ($row = $result->fetch_assoc()) { 
            $promotion = $row;
            // $base_path_blog = 'assets/images/wellness/';
            $imagePath = $base_path_blog . $row['image'];
            if (!file_exists($imagePath)) {
                $imagePath = 'assets/images/default.png';
            }
    ?>
        <div class="promotion-body col-lg-4 col-md-6 col-sm-12 p-5">
            <img src="<?php echo $imagePath; ?>" class="card-img" alt="Promotion Image">
            <div class="our-package-title">
                <?php echo htmlspecialchars($promotion['name_en']); ?>
            </div>
            <div class="package-description">
                <?php echo htmlspecialchars($promotion['details_en']); ?>
                <div class="package-price mt-4 text-bold"><?php echo number_format($promotion['price_discount']);?>฿</div>
                <div class="package-price mt-2">From 
                <span style="text-decoration: line-through;">
                    <?php echo number_format($promotion['price']);?>฿</div>
                </span>    
            </div>
            <div class="reserve-promo-btn">
                <d type="button" 
                    data-bs-toggle="modal" 
                    data-bs-target="#reserveModal"
                    data-promotion-id="<?php echo $promotion['id']; ?>"
                    data-promotion-name="<?php echo htmlspecialchars($promotion['name_en']); ?>"
                    data-promotion-details="<?php echo htmlspecialchars($promotion['details_en']); ?>"
                    data-promotion-price="<?php echo number_format($promotion['price_discount']); ?>">
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
                <h5 class="modal-title" id="reserveModalLabel">Reserve Your Promotion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="promotion-details mb-3">
                    <h1 class="promotion-name"></h1>
                    <h4 class="promotion-description"></h2>
                    <h3><strong>Price:</strong> <span class="promotion-price"></span></h3>
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
    const promotionNameElement = reserveModal.querySelector('.promotion-name');
    const promotionDescriptionElement = reserveModal.querySelector('.promotion-description');
    const promotionPriceElement = reserveModal.querySelector('.promotion-price');
    const promotionIdInput = reserveModal.querySelector('input[name="promotion_id"]');

    // Add an event listener to the modal
    reserveModal.addEventListener('show.bs.modal', function (event) {
        // Get the button that triggered the modal
        const button = event.relatedTarget;

        // Extract promotion details from the button's data attributes
        const promotionId = button.getAttribute('data-promotion-id');
        const promotionName = button.getAttribute('data-promotion-name');
        const promotionDetails = button.getAttribute('data-promotion-details');
        const promotionPrice = button.getAttribute('data-promotion-price');

        // Update the modal content with the promotion details
        promotionNameElement.textContent = promotionName;
        promotionDescriptionElement.textContent = promotionDetails;
        promotionPriceElement.textContent = promotionPrice + ' THB';
        promotionIdInput.value = promotionId;
    });
</script>
</body>
</html>
<script src="assets/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>