<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = "support@grafordcollege.com";
    $subject = "New contact Form Submission";
    
    $fullName = $_POST["fullName"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $message = $_POST["message"];
    
    $headers = "From: $email" . "\r\n" .
               "Reply-To: $email" . "\r\n" .
               "X-Mailer: PHP/" . phpversion();
    
    $mailBody = "Full Name: $fullName\nPhone: $phone\nEmail: $email\nMessage:\n$message";
    
    // Attempt to send the email
    if (mail($to, $subject, $mailBody, $headers)) {
        // Email sent successfully
        // Optionally, you can redirect the user to a thank-you page
        header("Location: thank_you.html");
        exit;
    } else {
        // Email failed to send
        echo "Error sending email. Please try again later.";
    }
}
?>

