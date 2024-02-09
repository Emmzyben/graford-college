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
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables to store user input and error message
$userInputMatnumber = "";
$userInputPassword = "";
$errorMsg = "";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input from the form
    $userInputMatnumber = $_POST["Matnumber"];
    $userInputPassword = $_POST["password"];

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM accounts WHERE matriculationNumber = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userInputMatnumber);
    $stmt->execute();
    
    // Bind variables to the result set
    $stmt->bind_result($id, $fullName, $hometown, $lga, $state, $nationality, $dateOfBirth, $email, $schools, $course, $phone, $address, $maritalStatus, $religion, $qualification, $passportImagePath, $identificationImagePath, $Fees_paid, $payment_type, $matriculationNumber, $examNumber, $created_at, $password);

    if ($stmt->fetch()) {
        // User found, compare the plain password
        if ($userInputPassword == $password) {
            // User authentication successful
            $_SESSION['Matnumber'] = $userInputMatnumber; 
            $_SESSION['last_activity'] = time();
            echo '<script>setTimeout(function() { window.location = "overview1.php"; }, 1500);</script>';
        } else {
            // User authentication failed
            $errorMsg = "Authentication failed. Please check your matric number and password.";
        }
    } else {
        // User not found
        $errorMsg = "Authentication failed. Please check your matric number and password.";
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="images/logo.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="about.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>

</head>
<body>
<header id="cover">
    <div style="background-color: #0e0e8822;">
         <div id="firstUp">
           <div><p><i class="fa fa-phone-square"></i> +234 (0) 8067333223 <span id="sp"><i class="fa fa-phone-square"></i> +234 (0) 8098883608 </span></p></div>
            <div style="text-align: right;"><p><a style="color: white;text-decoration: none;" href="mailto:support@grafordcollege.com"><i class="fa fa-share-square-o"></i> support@grafordcollege.com</a></p></div>
         </div>
           <div style="display: flex;flex-direction: row;">
               <span id="side"><img src="images/logo.jpg" alt="logo"  ></span>
                <span><h1>GRAFORD COLLEGE OF MARITIME & AVIATION STUDIES</h1></span>
               <span id="side" style="text-align:right;">
                   <ul>
                   <li><a href="register.php">REGISTER NOW!</a></li>
                   <li><a href="contact.html">CONTACT US</a></li>
                   </ul>
               </span>
           </div>

          </div></header> 

        
             <div id="bg">
                 <ul>
                     <li><a href="index.html">HOME</a></li>
                     <li class="nav-container">
                       <span id="hoverer">ABOUT</span> 
                        <ul id="dropdown">
                         <li><a href="about.html">ABOUT US</a></li> 
                         <li><a href="director.html">DIRECTOR PROFILE</a></li> 
                         <li><a href="staff.php">STAFF</a></li>
                        </ul>
                      </li>
                      <li class="nav-container">
                       <span id="hoverer">SCHOOLS</span> 
                        <ul id="dropdown" style="width: 300px;">
                           <li><a href="nautical.html">SCHOOL OF NAUTICAL STUDIES</a></li>
                           <li><a href="aviation.html">SCHOOL OF AVIATION</a></li>
                           <li><a href="diving.html">SCHOOL OF DIVING</a></li>
                           <li><a href="engineering.html">SCHOOL OF ENGINEERING</a></li>
                           <li><a href="maritime.html">SCHOOL OF MARITIME TRANSPORT & BUSINESS TECHNOLOGY</a></li>
                           <li><a href="food-science.html">SCHOOL OF FOOD SCIENCE</a></li>
                           <li><a href="training.html">SCHOOL OF VOCATIONAL TRAINING/REHABILITATION</a></li>
                           <li><a href="agriculture.html">SCHOOL OF AGRICULTURE</a></li>
                           <li><a href="sciences.html">SCHOOL OF SCIENCES</a></li>
                 
                        </ul>
                      </li>
   
                     <li class="nav-container">
                        <span id="hoverer">PORTAL LOGIN</span> 
                         <ul id="dropdown">
                          <li><a href="admissions.php">ADMISSION PORTAL</a></li> 
                          <li><a href="student.php">STUDENT PORTAL</a></li> 
                         </ul>
                       </li>
                       <li class="nav-container" >
                         <span id="hoverer">INFORMATION</span> 
                          <ul id="dropdown">
                            <li><a href="certificate.php">CERTIFICATE VERIFICATION</a></li> 
                           <li><a href="alumni.php">ALUMNI</a></li> 
                           <li><a href="blog.php">NEWS/BLOG</a></li>
                          </ul>
                        </li>
                     <li><a href="study.php">STUDY CENTER</a></li>
                 </ul>
             </div>
     

       
    <aside >
        <div style="width: 20%;"><img src="images/logo.jpg" alt="logo" ></div>
        <div style="text-align: center;padding-top: 15px;color: #090970;padding-left: 20px;"><h3>GRAF-COMAS</h3></div>
        <div  id="span" onclick="openNav()" style="cursor: pointer;">&#9776;</div>
    </aside>

<nav>
    <div id="mySidenav" class="sidenav">

      <img src="images/logo.jpg" alt="" id="img"><hr>
      <a href="index.html">Home</a>
      <a href="about.html">About Us</a>
      <a href="director.html">Director Profile</a>
      <a class="dropdown-item" onclick="toggleDropdown()" style="  background-color:#0e0e88;
      color: #fff;">
       Schools +
          <div class="sub-menu1" style="display: none;transition: 0.5s;background-color: #d3e4ee;
          color: #fff;">
            <a href="nautical.html">School of Nautical Studies</a>
            <a href="aviation.html">School Of Aviation</a>
            <a href="diving.html"> School Of Diving </a>
            <a href="engineering.html">School Of Engineering </a>
            <a href="maritime.html">School Of Maritime Transport & Business Technology </a>
            <a href="food-science.html">School Of Food Science</a>
            <a href="training.html">School Of Vocational Training/Rehabilitation</a>
            <a href="agriculture.html">School of Agriculture</a>
            <a href="sciences.html">School of Sciences</a>
          </div>
        </a>
     
        <script>
          function toggleDropdown() {
            const subMenu = document.querySelector('.sub-menu1');
            subMenu.style.display = (subMenu.style.display === 'none' || subMenu.style.display === '') ? 'block' : 'none';
          }
        </script>
      <a href="admissions.php">Admission Portal</a>
      <a href="student.php">Student Portal</a>
      <a href="staff.php">Staff Portal</a>
      <a href="certificate.php">Certificate Verification</a>
      <a href="alumni.php">Alumni</a>
      <a href="study.php">Study Center</a>
      <a href="blog.php">News/blog</a>
      <a href="register.php">Register</a>
      <a href="contact.html">Contact</a> 
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

<div id="container">
  <div class="hide"></div>
  <div style="margin: 20px;" id="border">
  <h2 style="border-left: 0px solid white;">Student Portal Login</h2>

<form action="" id="formdiv" method="post">
  <label for="">Mat number <span>*</span></label><br>
<input type="text" name="Matnumber" id=""><br>
  <label for="">Password<span>*</span></label><br>
<input type="password" name="password"><br>
<a href="password_reset.php" style="padding-left: 5px;">Forgot Password?</a><br><br>
<button type="submit"  id="submit">Login</button>
<?php
if (!empty($errorMsg)) {
    echo '<p style="color: red;">' . $errorMsg . '</p>';
}
?>
</form>

</div>
<div class="hide"></div>
</div>













<div id="overfoot">
  <footer>
<div style="display: flex;flex-direction: row;">
  <div><img src="images/logo.jpg" alt="logo" width="80px"></div>
  <div><h3 style="padding: 10px;">GRAF-COMAS</h3></div>
</div>
<hr>
<div id="foot">
<div>
<h3>ABOUT</h3>
<p><a href="about.html">About Us</a></p>
<p><a href="director.html">Director Profile</a></p>
</div>

<div>
  <h3>SCHOOLS</h3>
<p><a href="nautical.html">School Of Nautical Studies</a></p>
 <p> <a href="aviation.html">School Of Aviation</a></p>
  <p><a href="diving.html">School Of Diving</a></p>
 <p><a href="engineering.html">School Of Engineering</a></p> 
 <p><a href="maritime.html">School Of Maritime Transport & Business Technology</a></p> 
 <p><a href="food-science.html">School Of Food Science</a></p> 
   <p><a href="training.html">School Of Vocational Training/Rehabilitation</a></p> 
           <p><a href="agriculture.html">School of Agriculture</a></p>
           <p><a href="sciences.html">School of Sciences</a></p>
</div>

<div>
<h3>INFORMATION CENTER</h3>
<p><a href="blog.php">News and Blog</a></p>
<p><a href="certificate.php">Certificate Verification</a></p>
<p><a href="alumni.php">Alumni page</a></p>
</div>

<div>
  <h3>CONTACT</h3>
  <p><a style="color: white;text-decoration: none;" href="mailto:support@grafordcollege.com"><i class="fa fa-share-square-o"></i> support@grafordcollege.com</a></p>
  <p><i class="fa fa-phone-square" ></i> +234 (0) 8067333223</p>
  <p><i class="fa fa-phone-square" ></i> +234 (0) 8098883608</p>
</div>
</div>
<hr>
<div style="text-align: center;">
  <p>©2023  GRAF-COMAS- All rights reserved</p>
</div>
</footer>
</div>
    <script src="javascript.js"></script>
</body>
</html>