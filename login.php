<?php
$page = "auth";
include_once("includes/header.php");
require_once("includes/config.php");
?>

<div class="auth-wrapper">
    <h2>Login to your Account</h2>

    <form action="">
        <div class="form-group">
            <label for="name">Username or Email:</label>
            <input type="text" placeholder="Jane Doe">
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" >
        </div>
        <p>Forgot password?<a href="forgot_password.php"> Click here!</a></p>

        <button type="submit">Submit</button>
        <p>Don't have an account yet? <a href="signup.php">Register here.</a></p>
    </form>

</div>

<?php
include_once("includes/footer.php");
?>