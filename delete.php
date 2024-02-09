<?php
// delete.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'])) {
        $emailToDelete = $_POST['email'];

        // Implement the logic to delete the account associated with the provided email
        // Example:
        $DATABASE_HOST = 'localhost';
        $DATABASE_USER = 'grafordc_graford';
        $DATABASE_PASS = 'Gratia12345';
        $DATABASE_NAME = 'grafordc_graford';

        $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
        if (mysqli_connect_errno()) {
            exit('Failed to connect to MySQL: ' . mysqli_connect_error());
        }

        $deleteQuery = $con->prepare('DELETE FROM accounts WHERE email = ?');
        $deleteQuery->bind_param('s', $emailToDelete);

        if ($deleteQuery->execute()) {
              header("Location: register.php");
        } else {
             header("Location: register.php");
        }

        $deleteQuery->close();
        mysqli_close($con);
    } else {
        echo 'Email parameter not provided';
    }
} else {
    echo 'Invalid request method';
}
?>
