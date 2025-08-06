<?php
$page = "auth";
include_once("includes/header.php");
include_once("includes/config.php");
?>

<div class="auth-wrapper">
    <h2>Registration</h2>
    <p>Fill the form below to get started</p>
    <form action="">
        <div class="form-group">
            <label for="name">Enter name:</label>
            <input type="text" placeholder="Jane Doe">
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="you@email.com">
        </div>

        <button type="submit">Submit</button>
        <p>Already have an account. <a href="login.php">Login to your account.</a></p>
    </form>

</div>

<?php
include_once("includes/footer.php");
?>