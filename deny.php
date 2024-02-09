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

            // Delete the account
            $sqlDelete = "DELETE FROM accounts WHERE examNumber = ?";

            // Check if the prepare statement for the delete query was successful
            if ($stmtDelete = $conn->prepare($sqlDelete)) {
                $stmtDelete->bind_param("s", $userInputExamNumber);
                $stmtDelete->execute();

                // Check if any rows were affected
                if ($stmtDelete->affected_rows > 0) {
                    $stmtDelete->close();

                    // Account deleted successfully
                    $_SESSION['last_activity'] = time();
                    echo 'Account deleted successfully!';

                    // Send a rejection email to the retrieved email address
                    $to = $email;
                    $subject = "Admission Status";
                    $message = "Dear $fullName,\n\nWe regret to inform you that you were not offered admission to Graford College. However, we encourage you to try again at a later time.";
                    $headers = "From: grafordcollege_support@grafordcollege.com"; 

                  
                    if (mail($to, $subject, $message, $headers)) {
                        echo ' Rejection email sent successfully.';
                    } else {
                        echo ' Error sending rejection email. Please try again later.';
                    }

                    echo '<script>setTimeout(function() { window.location = "Admission_offer.php"; }, 1500);</script>';
                } else {
                    // No account found with the provided exam number
                    echo "No account found with the provided exam number.";
                }
            } else {
                echo "Error in the prepare statement for the delete query: " . $conn->error;
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
