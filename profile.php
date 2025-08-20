<?php
session_start();

$page = "profile";
include_once("includes/header.php");
require_once("includes/config.php");

if ($_SESSION['role'] === 'tutor') {
    try {
        $stmt = $pdo->prepare("
        SELECT *
        FROM tutor
        WHERE tutor_id = :tutor_id
        LIMIT 1
        ");

        $stmt->execute([":tutor_id" => $_SESSION["user_id"]]);

        if ($stmt->rowCount() === 1) {
            $tutor = $stmt->fetch(PDO::FETCH_ASSOC);

            $_SESSION["bio"] = $tutor['bio'];
            $_SESSION["hourly_rate"] = $tutor['hourly_rate'];
            $_SESSION["experience_yrs"] = $tutor['experience_yrs'];
            $_SESSION["education"] = $tutor['education'];
            $_SESSION["location"] = $tutor['location'];
        } else {
            echo "error: Profile not found";
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}
?>

<div class="profile-container">

    <div class="left">
        <img src="<?php echo $_SESSION["profile_picture"]; ?>" alt="profile picture" srcset="">

        <h2> <?php echo $_SESSION["fullname"];?> </h2>

        <div class="user-info">
            <p>Role: <?php echo $_SESSION["role"]; ?></p>
            <p>Phone no: <?php echo $_SESSION["phone"]; ?></p>
            <p>Email: <?php echo $_SESSION["email"]; ?></p>
        </div>
    </div>

    <div class="right">
        
        <?php if ($_SESSION["role"] == "tutor"): ?>

            <div class="tutor_profile_info">
                <!-- make the information box fill an entire row; to be done in the styling-->
                    <div class="tutor_profile_info_label">
                        <h3>Your Information</h3>
                    </div>
                    
                    <!-- make all the information have a strong tag --> 
                    <div class="info_wrapper">
                        <p> <strong>Bio:</strong>  <?php echo $_SESSION["bio"]; ?></p>
                        <p>Rate: <?php echo $_SESSION["hourly_rate"]; ?></p>
                        <p>Experience: <?php echo $_SESSION["experience_yrs"]; ?></p>
                        <p>Education: <?php echo $_SESSION["education"]; ?></p>
                        <p>Location: <?php echo $_SESSION["location"]; ?></p>
                    </div>
            </div>

            <div class="tutor_profile_info">
                <div class="tutor_profile_info_label">
                    <h3>Bookings</h3>
                </div>
                
                <div class="info_wrapper">
                    <?php
                        try {
                            $sql = "
                                SELECT b.id, b.session_date, b.start_time, b.end_time, b.status, u.fullname AS parent_name
                                FROM bookings b
                                JOIN users u ON b.parent_id = u.id
                                WHERE b.tutor_id = :tutor_id
                                ORDER BY b.session_date DESC
                            ";

                            $stmt = $pdo->prepare($sql);

                            // Use bindValue for clarity
                            $stmt->bindValue(':tutor_id', $_SESSION['user_id'], PDO::PARAM_INT);

                            $stmt->execute();

                            if ($stmt->rowCount() > 0) {
                                while ($booking = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<p><strong>Parent:</strong> " . htmlspecialchars($booking['parent_name']) . "<br>";
                                    echo "<strong>Date:</strong> " . htmlspecialchars($booking['session_date']) .
                                        " <strong>Time:</strong> " . htmlspecialchars($booking['start_time']) .
                                        " - " . htmlspecialchars($booking['end_time']) . "<br>";
                                    echo "<strong>Status:</strong> " . ucfirst(htmlspecialchars($booking['status'])) . "</p>";

                                    if ($booking['status'] === 'pending') {
                                        echo "<form action='accept_booking.php' method='POST'>
                                                <input type='hidden' name='booking_id' value='" . htmlspecialchars($booking['id']) . "'>
                                                <button type='submit'>Accept Booking</button>
                                            </form>";
                                    }
                                }
                            } else {
                                echo "No bookings found.";
                            }
                        } catch (PDOException $e) {
                            echo "Database error: " . $e->getMessage();
                        }
                    ?>
                </div>
            </div>

            <div class="tutor_profile_info">
                <div class="tutor_profile_info_label">
                    <h3>Available Days</h3>
                </div>

                <div class="info_wrapper">
                    <?php
                    try {
                        $stmt = $pdo->prepare("
                        SELECT day, start_time, end_time
                        FROM availability
                        WHERE tutor_id = :tutor_id
                        ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
                        ");
                        $stmt->execute([":tutor_id" => $_SESSION["user_id"]]);

                        if ($stmt->rowCount() > 0) {
                            while ($availability = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<p><strong>" . $availability['day'] . ":</strong> " . $availability['start_time'] . " - " . $availability['end_time'] . "</p>";
                            }
                        } else {
                            echo "No availability set.";
                        }
                    } catch (PDOException $e) {
                        echo "Database error: " . $e->getMessage();
                    }
                    ?>

                    <h4>Edit Availability</h4>
                    <form action="update_availability.php" method="POST">
                        <label for="day">Day:</label>
                        <select name="day" id="day">
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                        <label for="start_time">Start Time:</label>
                        <input type="time" name="start_time" required>
                        <label for="end_time">End Time:</label>
                        <input type="time" name="end_time" required>
                        <button type="submit">Update Availability</button>
                    </form>
                </div>
            </div>


            <div class="tutor_profile_info">
                <div class="tutor_profile_info_label">
                    <h3>Reviews</h3>
                </div>

                <div class="info_wrapper">
                    <?php
                    try {
                        $stmt = $pdo->prepare("
                        SELECT r.rating, r.comment, r.created_at, u.fullname as parent_name
                        FROM reviews r
                        JOIN users u ON r.parent_id = u.id
                        WHERE r.tutor_id = :tutor_id
                        ORDER BY r.created_at DESC
                        ");
                        $stmt->execute([":tutor_id" => $_SESSION["user_id"]]);

                        if ($stmt->rowCount() > 0) {
                            while ($review = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<p><strong>Parent:</strong> " . $review['parent_name'] . "<br>";
                                echo "<strong>Rating:</strong> " . $review['rating'] . "/5<br>";
                                echo "<strong>Comment:</strong> " . $review['comment'] . "<br>";
                                echo "<strong>Reviewed on:</strong> " . $review['created_at'] . "</p>";
                            }
                        } else {
                            echo "No reviews yet.";
                        }
                    } catch (PDOException $e) {
                        echo "Database error: " . $e->getMessage();
                    }
                    ?>
                </div>
            </div>

            <div class="tutor_profile_info">
                <div class="tutor_profile_info_label">
                    <h3>Your Subjects</h3>
                </div>

                <div class="info_wrapper">
                    <?php
                    try {
                        $stmt = $pdo->prepare("
                        SELECT s.name
                        FROM tutor_subjects ts
                        JOIN subjects s ON ts.subject_id = s.id
                        WHERE ts.tutor_id = :tutor_id
                        ");
                        $stmt->execute([":tutor_id" => $_SESSION["user_id"]]);

                        if ($stmt->rowCount() > 0) {
                            while ($subject = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<p>" . $subject['name'] . "</p>";
                            }
                        } else {
                            echo "No subjects listed.";
                        }
                    } catch (PDOException $e) {
                        echo "Database error: " . $e->getMessage();
                    }
                    ?>

                    <h4>Add or Remove Subjects</h4>
                    <form action="update_subjects.php" method="POST">
                        <label for="subject_id">Subject:</label>
                        <select name="subject_id" id="subject_id">
                            <?php
                            // Populate with available subjects
                            $stmt = $pdo->prepare("SELECT * FROM subjects");
                            $stmt->execute();
                            while ($subject = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $subject['id'] . "'>" . $subject['name'] . "</option>";
                            }
                            ?>
                        </select>
                        <button type="submit">Add Subject</button>
                    </form>
                </div>
            </div>

        <?php endif; ?>
    </div>

    

    <!-- get data like availability, bookings, payments, reveiws -->

</div>

<?php
include_once("includes/footer.php");
?>
