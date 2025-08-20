<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- this file contains my header for each page in the website -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <?php if ($page == "index"): ?>
        <title>Welcome - Tutor Connect</title>
        <link rel="stylesheet" href="css/index.css">
    <?php elseif ($page == "auth"): ?>
        <title>Authentication</title>
        <link rel="stylesheet" href="css/auth.css">
    <?php elseif ($page == "profile"): ?>
        <title><?php echo $_SESSION["fullname"]; ?></title>
        <link rel="stylesheet" href="css/profile.css">
    <?php endif; ?>

    <!-- css styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <nav>
        <div class="logo">Tutor Connect</div>
        <div class="navlinks">
            <ul>
                <li><a href="#">Tutors</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Contact</a></li>
            </ul>

                
            <div class="nav-cta">
                <?php if (isset($_SESSION["fullname"])): ?>
                    <a class="nav-cta-logout" href="logout.php">Log out</a>
                <?php else: ?>    
                    <a class="nav-cta-signup" href="signup.php">Sign up</a>
                    <a class="nav-cta-login" href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="hamburger" id="hamburger">
            &#9776;
        </div>
    </nav>
</body>

