<?php

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

$query = "SELECT id, fullName, hometown, lga, state, nationality, dateOfbirth, email, school, course, phone, address, MaritalStatus, religion, qualification, passport_image_path, identification_image_path, matriculationNumber, examNumber, created_at FROM accounts WHERE matriculationNumber = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $matnumber);
$stmt->execute();
$stmt->bind_result($id, $fullName, $hometown, $lga, $state, $nationality, $dateOfbirth, $email, $school, $course, $phone, $address, $MaritalStatus, $religion, $qualification, $passport_image_path, $identification_image_path, $matriculationNumber, $examNumber, $created_at);

if ($stmt->fetch()) {
    // Fetch successful, use the retrieved data as needed
    $accounts = array(
        'id' => $id,
        'fullName' => $fullName,
        'hometown' => $hometown,
        'lga' => $lga,
        'state' => $state,
        'nationality' => $nationality,
        'dateOfbirth' => $dateOfbirth,
        'email' => $email,
        'school' => $school,
        'course' => $course,
        'phone' => $phone,
        'address' => $address,
        'MaritalStatus' => $MaritalStatus,
        'religion' => $religion,
        'qualification' => $qualification,
        'passport_image_path' => $passport_image_path,
        'identification_image_path' => $identification_image_path,
        'matriculationNumber' => $matriculationNumber,
        'examNumber' => $examNumber,
        'created_at' => $created_at
    );
} else {
    $errorMessage .= 'Failed to fetch account information from the database. ';
}

$stmt->close();

// Continue with the rest of your code...

?>



<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="images/logo.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="student.css">
    <style>
        #notice>ul>li{
         margin-bottom:15px
        }
    </style>
</head>
<body>
<aside >
        <div style="width: 20%;"><img src="images/logo.jpg" alt="logo" ></div>
        <div style="text-align: center;padding-top: 15px;color: #0e0e88;padding-left: 20px;"><h3>GRAF-COMAS</h3></div>
        <div  id="span" onclick="openNav()" style="cursor: pointer;">&#9776;</div>
      </aside>
      <nav>
        <div id="mySidenav" class="sidenav">
        
        <img src="images/logo.jpg" alt="" id="img"><hr>
        <a href="overview1.php" >Overview</a>
        <a href="Accounts.php">Fees payment</a>
        <a href="settings.php" >Settings</a>
        <a href="logout.php?&last_activity=' . htmlspecialchars($lastActivityTimestamp) . '">Logout</a>
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
                <li ><a href="overview1.php" id="focus1">Overview</a></li>
                <li ><a href="Accounts.php" id="focus2">Fees payment</a></li>
                <li ><a href="settings.php" id="focus2">Settings</a></li>
                <li><a href="logout.php?&last_activity=' . htmlspecialchars($lastActivityTimestamp) . '" id="focus2">Logout</a></li>
            </ul>
        </div>  
</div>
  
<div style="overflow: auto;padding: 20px;">
<h2 style="color:white;background-color:#0e0e88;padding:20px">Personal Profile</h2>
    <div id="overview"> 
<div>
<?php
// Assuming $accounts is the array containing the account information

if (isset($accounts) && (is_array($accounts) || is_object($accounts)) && count($accounts) > 0) {
    if (is_array($accounts[0])) {
        // Multi-dimensional array
        foreach ($accounts as $account) {
            displayAccountInformation($account);
        }
    } else {
        // Single-dimensional array
        displayAccountInformation($accounts);
    }
} else {
    echo '<p>No account information available.</p>';
}

function displayAccountInformation($account) {
    // Extract information from the account array
    $passportImagePath = isset($account['passport_image_path']) ? $account['passport_image_path'] : '';
   

    // Check if the image file exists
    if (file_exists($passportImagePath)) {
        // Display the image with the specified HTML tag
        echo '<img src="' . htmlspecialchars($passportImagePath) . '" alt="Passport Photo">';
    } else {
        echo '<p>Passport Photo not found.</p>';
    }
}
?>



</div>
<div>




</div>
        </div>

<?php
// Your existing PHP code...

// Check if user data is available
if (!empty($accounts)) {
    ?>
    <p><strong>Full Name:</strong> <?php echo $accounts['fullName']; ?></p>
    <p><strong>Hometown:</strong> <?php echo $accounts['hometown']; ?></p>
    <p><strong>LGA:</strong> <?php echo $accounts['lga']; ?></p>
    <p><strong>State:</strong> <?php echo $accounts['state']; ?></p>
    <p><strong>Nationality:</strong> <?php echo $accounts['nationality']; ?></p>
    <p><strong>Date of Birth:</strong> <?php echo $accounts['dateOfbirth']; ?></p>
    <p><strong>Email:</strong> <?php echo $accounts['email']; ?></p>
    <p><strong>School:</strong> <?php echo $accounts['school']; ?></p>
    <p><strong>course:</strong> <?php echo $accounts['course']; ?></p>
    <p><strong>Phone:</strong> <?php echo $accounts['phone']; ?></p>
    <p><strong>Address:</strong> <?php echo $accounts['address']; ?></p>
    <p><strong>Marital Status:</strong> <?php echo $accounts['MaritalStatus']; ?></p>
    <p><strong>Religion:</strong> <?php echo $accounts['religion']; ?></p>
    <p><strong>Qualification:</strong> <?php echo $accounts['qualification']; ?></p>
    <p><strong>Matriculation Number:</strong> <?php echo $accounts['matriculationNumber']; ?></p>
    <p><strong>Exam Number:</strong> <?php echo $accounts['examNumber']; ?></p>
    <p><strong>Created At:</strong> <?php echo $accounts['created_at']; ?></p>

    <?php
} else {
    // Display an error message if user data is not available
    echo '<p>Error: ' . $errorMessage . '</p>';
}
?>
        </div>  

</main>
  

         


</body>
</html>