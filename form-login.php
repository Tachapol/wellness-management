<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Arura</title>

    <!-- CSS -->
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
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

    <!-- Section Login -->
    <section>
        <div class="container">
            <div class="wrapper">
                <div class="form-box-login">
                    <h2>Active Aura</h2>
                    <h3>Wellness Center</h3>
                    <form action="login.php" method="POST">
                        <div class="input-box-name">
                            <label class="ml-5">Email</label>
                            <input class="mx-5" type="email" name="email" required>
                        </div>
        
                        <div class="input-box-name">
                            <label>Password</label>
                            <input class="mx-5" type="password" name="password" required>
                        </div>
        
                        <div class="button-container">
                            <button class="btn-signUp my-5">LOGIN</button>
                        </div>
        
                        <div class="sign-up">
                            <p>Don’t have an account? <a href="../wellness-center/form-signup.php" class="sign-up-link">SIGN UP</a></p>
                        </div>
        
                    </form>
                </div>
            </div>
            </div>
    </section>

</body>
</html>
<script src="assets/js/main.js" defer></script>