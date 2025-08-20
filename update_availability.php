<?php
session_start();

require_once("includes/config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $pdo->prepare("
        REPLACE INTO availability (tutor_id, day, start_time, end_time)
        VALUES (:tutor_id, :day, :start_time, :end_time)
        ");
        $stmt->execute([
            ":tutor_id" => $_SESSION["user_id"],
            ":day" => $_POST["day"],
            ":start_time" => $_POST["start_time"],
            ":end_time" => $_POST["end_time"]
        ]);

        header("Location: profile.php");
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}
?>
