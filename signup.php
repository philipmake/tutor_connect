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
            <label for="name">Enter name</label>
            <input type="text" placeholder="Jane Doe">
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="you@email.com">
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="tel" name="phone" id="phone">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
        </div>

        <div class="form-group">
            <label for="password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password">
        </div>

        <p style="font-size: 1.2rem;">Are you a parent or a Tutor?</p>
        
        <div class="form-section">
            <label class="option">
                <input type="radio" name="choice" value="option1">
                <span>Parent</span>
            </label>
            <label class="option">
                <input type="radio" name="choice" value="option2">
                <span>Tutor</span>
            </label>
        </div>

        <button type="submit">Submit</button>
        <p>Already have an account. <a href="login.php">Login to your account.</a></p>
    </form>
</div>

<?php
include_once("includes/footer.php");
?>
