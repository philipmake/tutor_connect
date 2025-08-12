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
    <?php elseif ($page == "main3"): ?>
        <title>Main 3</title>
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

            <!-- add a session to ensure if 
            user is logged in, in order to display logi or signup buttons -->
            <div class="nav-cta">
                <a class="nav-cta-signup" href="signup.php">Sign up</a>
                <a class="nav-cta-login" href="login.php">Login</a>
            </div>
        </div>
        
        <div class="hamburger" id="hamburger">
            &#9776;
        </div>
    </nav>
</body>

