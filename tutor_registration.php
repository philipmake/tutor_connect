<?php
$page = "auth";
include_once("includes/header.php");
require_once("includes/config.php");

$errors = [];
$success = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = trim($_POST['role']);

    // Input Validations
    if (empty($fullname)) {
        $errors[] = "Full name is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }
    if (empty($phone) || !preg_match('/^[0-9]{10,15}$/', $phone)) {
        $errors[] = "Valid phone number is required.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }
    if (!in_array($role, ['tutor', 'parent'])) {
        $errors[] = "Role must be either 'tutor' or 'parent'.";
    }

    // If no errors, insert into database
    if (empty($errors)) {
        $hash_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("
                INSERT INTO users (role, fullname, email, phone, password)
                VALUES (:role, :fullname, :email, :phone, :password)
            ");

            $stmt->execute([
                ':role'     => $role,
                ':fullname' => $fullname,
                ':email'    => $email,
                ':phone'    => $phone,
                ':password' => $hash_password
            ]);

            $success = "Registration successful!";
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
   
    // redirect to complete registration
    if ($role === "parent") {
        header("Location: ");
    } elseif ($role === "tutor") {
        header("Location: tutor_registration.php");
    }

}

?>

<div class="auth-wrapper">
    <h2>Registration</h2>
    <p>Fill the form below to get started</p>

    <!---- Display messages ---->
    
    <?php if (!empty($errors)): ?>
        <div class="error" style="color: red;">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-------------------------->
    
    <?php if ($success): ?>
        <div class="success" style="color: green;">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <!-------------------------->
    
    <form action="" method="POST">
        <div class="form-group">
            <label for="name">Enter name</label>
            <input type="text" name="fullname" id="fullname" placeholder="Jane Doe" value="<?= htmlspecialchars($fullname ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="you@email.com" value="<?= htmlspecialchars($email ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="tel" name="phone" id="phone" value="<?= htmlspecialchars($phone ?? '') ?>">
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
                <input type="radio" name="role" value="parent" <?= (isset($role) && $role === 'parent') ? 'checked' : '' ?>>
                <span>Parent</span>
            </label>
            <label class="option">
                <input type="radio" name="role" value="tutor" <?= (isset($role) && $role === 'tutor') ? 'checked' : '' ?> >
                <span>Tutor</span>
            </label>
        </div>

        <button type="submit" name="submit">Submit</button>
        <p>Already have an account. <a href="login.php">Login to your account.</a></p>
    </form>
</div>

<?php
include_once("includes/footer.php");
?>
