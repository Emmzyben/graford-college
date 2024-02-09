<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validate and sanitize the incoming data
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
$paymentType = filter_input(INPUT_POST, 'paymentType', FILTER_SANITIZE_STRING);

if ($email === false || $amount === false || $paymentType === false) {
    // Invalid input, handle the error accordingly
    echo 'Invalid input data.';
    exit();
}

// Database connection parameters
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'grafordc_graford';
$DATABASE_PASS = 'Gratia12345';
$DATABASE_NAME = 'grafordc_graford';

// Create database connection
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Prepare and execute the SQL query to update the account
$query = "UPDATE accounts SET Fees_paid = ?, payment_type = ? WHERE email = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("dss", $amount, $paymentType, $email);
$result = $stmt->execute();

if ($result) {
    echo 'Update successful';
} else {
    echo 'Failed to update data.';
}

// Close database connection
$stmt->close();
mysqli_close($con);
?>
