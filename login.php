<?php
session_start();

$page = "auth";
include_once("includes/header.php");
require_once("includes/config.php");

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // validation
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Enter a valid email address.";
    }
    if (empty($password) || strlen($password) < 6) {
        $errors[] = "Password is required!";
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
            SELECT id, role, fullname, password, email, phone, profile_picture 
            FROM users 
            WHERE email = :email 
            LIMIT 1
            ");

            $stmt->execute([":email" => $email]);

            if ($stmt->rowCount() === 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // verify password
                if (password_verify($password, $user["password"])) {
                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["fullname"] = $user["fullname"];
                    $_SESSION["email"] = $user["email"];
                    $_SESSION["role"] = $user["role"];

                    header("Location: profile.php");

                    exit;
                } else {
                    $errors[] = "Invalid Password";
                }
            } else {
                $errors[] = "No account found with the provided information.";
            }

        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}

?>

<div class="auth-wrapper">
    <h2>Login to your Account</h2>

    <form action="" method="POST">
        <div class="form-group">
            <label for="name">Email:</label>
            <input type="text" name="email" id="email" placeholder="Jane Doe">
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