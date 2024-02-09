<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
session_start();
$_SESSION['last_activity'] = time();
if (!isset($_SESSION['Matnumber'])) {
    // Redirect to the login page
    header("Location: student.php");
    exit();
}

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'grafordc_graford';
$DATABASE_PASS = 'Gratia12345';
$DATABASE_NAME = 'grafordc_graford';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$matnumber = $_SESSION['Matnumber'];

$errorMessage = ''; // Variable to store error messages
$successMessage = ''; // Variable to store success message

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = mysqli_real_escape_string($con, $_POST['new_password']);
    $confirmPassword = mysqli_real_escape_string($con, $_POST['confirm_password']);

    // Check if new password and confirm password match
    if ($newPassword !== $confirmPassword) {
        $errorMessage .= 'New password and confirm password do not match.';
    } else {
        // Update the password in the database without hashing
        $updateQuery = "UPDATE accounts SET password = ? WHERE matriculationNumber = ?";
        $stmt = $con->prepare($updateQuery);
        $stmt->bind_param("ss", $newPassword, $matnumber);

        if ($stmt->execute()) {
            $successMessage = 'Password updated successfully.';
            $_SESSION['last_activity'] = time();
        } else {
            $errorMessage .= 'Failed to update password.';
            $_SESSION['last_activity'] = time();
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
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="student.css">

</head>
<body>
<aside >
        <div style="width: 20%;"><img src="images/logo.jpg" alt="logo" ></div>
        <div style="text-align: center;padding-top: 15px;color: #090970;padding-left: 20px;"><h3>GRAF-COMAS</h3></div>
        <div  id="span" onclick="openNav()" style="cursor: pointer;">&#9776;</div>
      </aside>
      <nav>
        <div id="mySidenav" class="sidenav">
        
        <img src="images/logo.jpg" alt="" id="img"><hr>
        <a href="overview1.php" >Overview</a>
        <a href="Accounts.php">Fees payment</a>
        <a href="settings.php" >Settings</a>
        <a href="logout.php?csrf_token=' . htmlspecialchars($csrfToken) . '">Logout</a>
        </div>
        <script>
        
        function myFunction(x) {
        x.classList.toggle("change");
        }
        
        var open = false;
        
        function openNav() {
        var sideNav = document.getElementById("mySidenav");
        
        if (sideNav.style.width === "0px" || sideNav.style.width === "") {
          sideNav.style.width = "250px";
          open = true;
        } else {
          sideNav.style.width = "0";
          open = false;
        }
        }
        </script>
        </nav>

<main>
<div id="show">
        <div id="side">
            <div style="display: flex;flex-direction: row;margin: 10px;">
             <span><img src="images/logo.jpg" alt="logo" width="70px" style="border-radius: 50px;"></span>
            <span style="padding-left: 10px;"><h3>GRAF-COMAS</h3></span>
            </div>
            <div style="text-align: center;"><p>Student Dashboard</p></div>
            <hr>
            <ul>
                <li ><a href="overview1.php" id="focus2">Overview</a></li>
                <li ><a href="Accounts.php" id="focus2">Fees payment</a></li>
                <li ><a href="settings.php" id="focus1">Settings</a></li>
                <li><a href="logout.php?&last_activity=' . htmlspecialchars($lastActivityTimestamp) . '" id="focus2">Logout</a></li>
            </ul>
        </div>  
</div>
  
<div style="overflow: auto;padding: 20px;" id="role">
        <div id="pass">
            <h2>Change your password</h2>
           
            <form action=" " method="post">
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


</body>
</html>