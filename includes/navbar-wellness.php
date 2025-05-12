<?php 
session_start();
$file_name = basename($_SERVER['SCRIPT_FILENAME'], ".php");

function isActive($page) {
    global $file_name;
    return $file_name == $page ? 'active' : '';
}
?>

<nav id="navbar" class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <span class="navbar-brand" href="index.php">
            <img src="assets/images/wellness/logo1.png" width="150" class="d-inline-block align-center" alt="Wellness Center Logo">
        </span>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarKey" aria-controls="navbarKey" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    
        <div class="collapse navbar-collapse" id="navbarKey">
            <ul class="navbar-nav justify-content-center mx-auto">
                <li class="nav-item <?php echo isActive('index'); ?>">
                    <a class="nav-link mx-5" href="index.php">About us<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item <?php echo isActive('index'); ?>">
                    <a class="nav-link mx-5 " href="index.php#package">Package</a>
                </li>
                <li class="nav-item <?php echo isActive('promotion'); ?>">
                    <a class="nav-link mx-5" href="promotion.php">Promotion</a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Show Logout if the user is logged in -->
                    <li class="nav-item <?php echo isActive('profile'); ?>">
                        <a class="nav-link mx-5" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item <?php echo isActive('logout'); ?>">
                        <a class="nav-link mx-5" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <!-- Show Login if the user is not logged in -->
                    <li class="nav-item <?php echo isActive('form-login'); ?>">
                        <a class="nav-link mx-5" href="form-login.php">Login</a>
                    </li>
                    <li class="nav-item <?php echo isActive('form-signup'); ?>">
                        <a class="nav-link mx-5" href="form-signup.php">Sign Up</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>  
    </div>
</nav>