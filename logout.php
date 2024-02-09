<?php
// In logout.php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Start or resume the session
session_start();

if (isset($_SESSION['Matnumber'])) {
    // Check if the last activity timestamp is present
    if (isset($_GET['last_activity'])) {
        $lastActivityTimestamp = (int)$_GET['last_activity']; // Ensure it's an integer

        // Check if the idle time has not exceeded a certain threshold (e.g., 10 minutes)
        $idleTimeThreshold = 10 * 60;
        if ((time() - $lastActivityTimestamp) <= $idleTimeThreshold) {
            // Update the last activity timestamp before redirecting
            $_SESSION['last_activity'] = time();

            // Redirect to the login page (or any other page)
            header("Location: student.php");
            exit();
        } else {
            // Idle time exceeded, destroy the session and then redirect to student.php
            session_unset();
            session_destroy();

            // Redirect to the login page (or any other page)
            header("Location: student.php");
            exit();
        }
    } else {
        // Last activity timestamp not provided, destroy the session and then redirect to student.php
        session_unset();
        session_destroy();

        // Redirect to the login page (or any other page)
        header("Location: student.php");
        exit();
    }
} else {
    // User not logged in, handle accordingly (e.g., log the attempt, deny access)
    header("Location: student.php");
    exit();
}
?>

