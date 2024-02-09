<?php
session_start();
$_SESSION['last_activity'] = time();

$servername = 'localhost';
$username = "grafordc_graford";
$password = "Gratia12345";
$database = "grafordc_graford";

$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
    die();
}

// Initialize variables to store user input and error message
$userInputExamNumber = "";
$errorMsg = "";
$fullName = "";
$matriculationNumber = "";
$email = "";
$password = "";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input from the form
    $userInputExamNumber = $_POST["examNumber"];

    // Use prepared statement to prevent SQL injection
    $sqlSelect = "SELECT fullName, matriculationNumber, password, email, examNumber FROM accounts WHERE examNumber = ?";

    // Check if the prepare statement was successful
    if ($stmtSelect = $conn->prepare($sqlSelect)) {
        $stmtSelect->bind_param("s", $userInputExamNumber);
        $stmtSelect->execute();

        // Bind variables to the result set
        $stmtSelect->bind_result($fullName, $matriculationNumber, $password, $email, $userInputExamNumber);

        if ($stmtSelect->fetch()) {
            // Close the select statement
            $stmtSelect->close();

            // Insert data into the "admissions" table
            $sqlInsert = "INSERT INTO admissions (fullName, matriculationNumber, password, examNumber) VALUES (?, ?, ?, ?)";

            // Check if the prepare statement for the insert query was successful
            if ($stmtInsert = $conn->prepare($sqlInsert)) {
                $stmtInsert->bind_param("ssss", $fullName, $matriculationNumber, $password, $userInputExamNumber);
                $stmtInsert->execute();

                // Close the insert statement
                $stmtInsert->close();

                $_SESSION['last_activity'] = time();

                // Send an email to the retrieved email address
                $to = $email;
                $subject = "Admission Offer";
                $message = "Dear $fullName,\n\nCongratulations! You have been offered admission to Graford College.Here are login details.\n\nMatriculation Number: $matriculationNumber\nPassword: $password\n\nPlease Preceed to your student portal to change your password to a more secure one, Once again welcome to a seamless academic experience";
                $headers = "From:grafordcollege_support@grafordcollege.com"; // Replace with your email address

                // Attempt to send the email
                if (mail($to, $subject, $message, $headers)) {
                    echo 'SUCCESS! Email sent successfully.';
                    echo '<script>setTimeout(function() { window.location = "Admission_offer.php"; }, 1500);</script>';
                } else {
                    echo 'Error sending email. Please try again later.';
                }
            } else {
                echo "Error in the prepare statement for the insert query: " . $conn->error;
            }
        } else {
            // User not found
            echo "User with the provided exam number not found.";
        }
    } else {
        echo "Error in the prepare statement for the select query: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
