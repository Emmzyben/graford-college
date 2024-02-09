<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    // Retrieve email from the POST data
    $email = $_POST['email'];

    // Database connection details
   $DATABASE_HOST = 'localhost';
$DATABASE_USER = 'grafordc_graford';
$DATABASE_PASS = 'Gratia12345';
$DATABASE_NAME = 'grafordc_graford';

    // Create a database connection
    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    if (mysqli_connect_errno()) {
        die('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    // Prepare and execute a query to retrieve exam number and full name
    $query = $con->prepare('SELECT examNumber, fullName FROM accounts WHERE email = ?');
    $query->bind_param('s', $email);
    $query->execute();
    $query->bind_result($examNumber, $fullName);
    $query->fetch();
    $query->close();

    // Close the database connection
    mysqli_close($con);

    if ($examNumber && $fullName) {
  
        $subject = 'Registration Successful';
        $message = 'Dear ' . $fullName . '';
        $message .= 'Thank you for registering at Graford College. Your registration was successful.';
        $message .= 'Your exam number is: ' . $examNumber . '';
         $message .= 'Check the blogs page of the website or contact support to know the date and venue of the entrace exam,come with this exam number.';
        $message .= 'Best regards,Graford College';

        $headers = 'From: grafordcollege_support@grafordcollege.com'; 

        // Send email
        if (mail($email, $subject, $message, $headers)) {
            
        } else {
           
        }
    } else {
        
    }
} else {
    
}
?>
