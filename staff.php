<?php
// Database connection details
$servername = 'localhost';
$username = "grafordc_graford";
$password = "Gratia12345";
$database = "grafordc_graford";
// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to retrieve images and descriptions in descending order
$sql = "SELECT staffName, position, picture_path FROM staff ORDER BY created_at DESC";
$result = $conn->query($sql);

// Generate HTML for displaying images and descriptions
$html = '';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $picture_path = $row['picture_path'];
        $staffName = $row['staffName'];
        $position = $row['position'];


        // Generate HTML for each image and description
        $html .= '<div class="image-container" style="border:1px solid #e4e4e4;border-radius:10px,">';
        $html .= '<img src="' . $picture_path . '" style="height: 300px; width: 100%;">';
        $html .= '<p  style="margin-top:20px;text-align:center"><b> ' . $staffName . '</b></p>';
        $html .= '<p  style="margin-top:20px;text-align:center"> ' . $position . '</p>';
        $html .= '</div>';
    }
} else {
    $html = '<p>No images found.</p>';
}

// Close the database connection
$conn->close();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="images/logo.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff portal</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="about.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
<style>
  .image-container{
    width: 30%;
    box-shadow:2px 2px 10px gray;
    margin:10px;
  }
  @media screen and (max-width:700px) {
    .image-container{
    width: 100%;
    box-shadow:2px 2px 10px gray;
    margin:20px;
  }
  }
</style>
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




<!-- content -->
<h1 style="text-align:center">Meet Our Staff</h1>
<div class="image-gallery" style="display: flex; flex-direction: row; flex-wrap: wrap;justify-content:space-around">
    <?php echo $html; ?>
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
 <script>
      if (window.location.pathname.endsWith('.html')) {
          var newUrl = window.location.pathname.slice(0, -5); // Remove .html extension
          window.history.replaceState({}, document.title, newUrl); // Change the URL without reloading the page
      }
    </script>
</body>
</html>