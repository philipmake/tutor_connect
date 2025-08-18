<?php
$page = "auth";
include_once("includes/header.php");
require_once("includes/config.php");

$errors = [];
$success = "";

// Start session to get logged-in user
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bio = trim($_POST['bio']);
    $hourly_rate = trim($_POST['hourly_rate']);
    $experience_yrs = trim($_POST['experience_yrs']);
    $education = trim($_POST['education']);
    $location = trim($_POST['location']);
    $subjects = $_POST['subjects'] ?? [];
    $availabilities = $_POST['availability'] ?? []; // array of arrays with day, start_time, end_time

    // Validation
    if (empty($bio)) $errors[] = "Bio is required.";
    if (!is_numeric($hourly_rate) || $hourly_rate <= 0) $errors[] = "Hourly rate must be a positive number.";
    if (!is_numeric($experience_yrs) || $experience_yrs < 0) $errors[] = "Experience must be a valid number.";
    if (empty($education)) $errors[] = "Education is required.";
    if (empty($location)) $errors[] = "Location is required.";
    if (empty($subjects)) $errors[] = "Select at least one subject.";
    if (empty($availabilities)) $errors[] = "Provide at least one availability slot.";

    if (empty($errors)) {
        try {
            // Insert or update tutor details
            $stmt = $pdo->prepare("
                INSERT INTO tutor (tutor_id, bio, hourly_rate, experience_yrs, education, location)
                VALUES (:tutor_id, :bio, :hourly_rate, :experience_yrs, :education, :location)
                ON DUPLICATE KEY UPDATE
                    bio = VALUES(bio),
                    hourly_rate = VALUES(hourly_rate),
                    experience_yrs = VALUES(experience_yrs),
                    education = VALUES(education),
                    location = VALUES(location)
            ");

            $stmt->execute([
                ':tutor_id' => $user_id,
                ':bio' => $bio,
                ':hourly_rate' => $hourly_rate,
                ':experience_yrs' => $experience_yrs,
                ':education' => $education,
                ':location' => $location
            ]);

            // Reset tutor subjects
            $pdo->prepare("DELETE FROM tutor_subjects WHERE tutor_id = ?")->execute([$user_id]);
            $stmtSub = $pdo->prepare("INSERT INTO tutor_subjects (tutor_id, subject_id) VALUES (?, ?)");
            foreach ($subjects as $subj) {
                $stmtSub->execute([$user_id, $subj]);
            }

            // Reset tutor availability
            $pdo->prepare("DELETE FROM availability WHERE tutor_id = ?")->execute([$user_id]);
            $stmtAvail = $pdo->prepare("INSERT INTO availability (tutor_id, day, start_time, end_time) VALUES (?, ?, ?, ?)");
            foreach ($availabilities as $slot) {
                if (!empty($slot['day']) && !empty($slot['start_time']) && !empty($slot['end_time'])) {
                    $stmtAvail->execute([$user_id, $slot['day'], $slot['start_time'], $slot['end_time']]);
                }
            }

            $success = "Tutor profile setup completed successfully!";
        
            // redirect to profile page
            header("Location: profile.php");
            
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}

// Fetch subjects for dropdown
$subjectsList = $pdo->query("SELECT * FROM subjects ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="auth-wrapper">
    <h2>Tutor Profile Setup</h2>
    <p>Complete your tutor profile to start teaching</p>

    <!-- Display errors -->
    <?php if (!empty($errors)): ?>
        <div class="error" style="color: red;">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Display success -->
    <?php if ($success): ?>
        <div class="success" style="color: green;">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label for="bio">Bio</label>
            <textarea name="bio" id="bio" rows="4"><?= htmlspecialchars($bio ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="hourly_rate">Hourly Rate (₦)</label>
            <input type="number" name="hourly_rate" id="hourly_rate" value="<?= htmlspecialchars($hourly_rate ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="experience_yrs">Years of Experience</label>
            <input type="number" name="experience_yrs" id="experience_yrs" value="<?= htmlspecialchars($experience_yrs ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="education">Education</label>
            <input type="text" name="education" id="education" value="<?= htmlspecialchars($education ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" name="location" id="location" value="<?= htmlspecialchars($location ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="subjects">Subjects</label><br>

            <?php foreach ($subjectsList as $subj): ?>
                <label>
                    <input type="checkbox" 
                        name="subjects[]" 
                        value="<?= $subj['id'] ?>" 
                        <?= (isset($subjects) && in_array($subj['id'], $subjects)) ? 'checked' : '' ?>>
                    <?= htmlspecialchars($subj['name']) ?>
                </label><br>
            <?php endforeach; ?>

        </div>


        <div class="form-group">
            <label>Availability</label>
            <div id="availability-wrapper">
                <div class="availability-slot">
                    <select name="availability[0][day]" id="day">
                        <option value="">Select Day</option>
                        <option>Monday</option>
                        <option>Tuesday</option>
                        <option>Wednesday</option>
                        <option>Thursday</option>
                        <option>Friday</option>
                        <option>Saturday</option>
                        <option>Sunday</option>
                    </select>
                    <input type="time" name="availability[0][start_time]">
                    <input type="time" name="availability[0][end_time]">
                </div>
            </div>
            <button type="button" onclick="addSlot()">+ Add More Availability</button>
        </div>

        <button type="submit" name="submit">Save Profile</button>
    </form>
</div>

<script>
let slotIndex = 1;
function addSlot() {
    const wrapper = document.getElementById('availability-wrapper');
    const div = document.createElement('div');
    div.classList.add('availability-slot');
    div.innerHTML = `
        <select name="availability[${slotIndex}][day]">
            <option value="">Select Day</option>
            <option>Monday</option>
            <option>Tuesday</option>
            <option>Wednesday</option>
            <option>Thursday</option>
            <option>Friday</option>
            <option>Saturday</option>
            <option>Sunday</option>
        </select>
        <input type="time" name="availability[${slotIndex}][start_time]">
        <input type="time" name="availability[${slotIndex}][end_time]">
    `;
    wrapper.appendChild(div);
    slotIndex++;
}
</script>

<?php
include_once("includes/footer.php");
?>
