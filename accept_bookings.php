<?php
require_once("includes/config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['booking_id'])) {
    try {
        $stmt = $pdo->prepare("
        UPDATE bookings
        SET status = 'confirmed'
        WHERE id = :booking_id
        ");

        $stmt->execute([":booking_id" => $_POST['booking_id']]);
        header("Location: profile.php"); // Redirect back to the profile page after updating
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}
?>
