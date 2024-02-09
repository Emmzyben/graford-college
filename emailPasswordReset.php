<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");


$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'grafordc_graford';
$DATABASE_PASS = 'Gratia12345';
$DATABASE_NAME = 'grafordc_graford';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}



$errorMessage = ''; // Variable to store error messages
$successMessage = ''; // Variable to store success message

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = mysqli_real_escape_string($con, $_POST['new_password']);
    $confirmPassword = mysqli_real_escape_string($con, $_POST['confirm_password']);
    $email= mysqli_real_escape_string($con, $_POST['email']);

    // Check if new password and confirm password match
    if ($newPassword !== $confirmPassword) {
        $errorMessage .= 'New password and confirm password do not match.';
    } else {
        // Update the password in the database without hashing
        $updateQuery = "UPDATE accounts SET password = ? WHERE email = ?";
        $stmt = $con->prepare($updateQuery);
        $stmt->bind_param("ss", $newPassword, $email);

        if ($stmt->execute()) {
            $successMessage = 'Password updated successfully.';
        } else {
            $errorMessage .= 'Failed to update password.';
        }
    }
}

$con->close();
?>




<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="images/logo.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset password</title>
    <link rel="stylesheet" href="student.css">

</head>
<body>

<main style="display: flex;align-items: center;justify-content: center;">
<div style="overflow: auto;padding: 20px;" id="role">
        <div id="pass">
            <h2>Change your password</h2>
           
            <form action=" " method="post">
            <label for="email">Enter your account email</label><br>
    <input type="email" id="email" name="email" required><br>
    <label for="new_password">Enter new password</label><br>
    <input type="password" id="new_password" name="new_password" required><br>

    <label for="confirm_password">Confirm new password</label><br>
    <input type="password" id="confirm_password" name="confirm_password" required><br>
    <span style="font-size:14px">Please enter a password you can remember!!</span><br>
    <button type="submit">Change</button>
    <?php
    if ($errorMessage !== '') {
        echo '<div style="color: red; text-align: left;">' . $errorMessage . '</div>';
    }

    if ($successMessage !== '') {
        echo '<div style="color: green; text-align: left;">' . $successMessage . '</div>';
    }
?>
</form>

        
        </div> 

      
    </div>


</main>

 <script>
    document.addEventListener('DOMContentLoaded', function () {
 
      var currentHash = window.location.hash;

 
      if (!currentHash) {
       
        window.location.hash = '#default';
      }

    });
  </script>
</body>
</html>