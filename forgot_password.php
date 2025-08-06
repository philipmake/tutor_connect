<?php
$page = "auth";
include_once("includes/header.php");
include_once("includes/config.php");
?>

<div class="auth-wrapper">
    <h2>Account Recovery</h2>
    <form action="">
        
        <div class="form-group">
            <label for="email">Enter registered email:</label>
            <input type="email" name="email" id="email" placeholder="you@email.com">
        </div>

        <button type="submit">Submit</button>
    </form>

</div>

<?php
include_once("includes/footer.php");
?>