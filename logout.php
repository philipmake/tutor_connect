<?php
session_start();   // start the session so it can be destroyed
session_unset();   // optional: remove all session variables
session_destroy(); // destroy the session

header("Location: login.php");
exit; // always good to stop further execution after redirect
?>
