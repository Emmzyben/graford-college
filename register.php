<?php
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'graford';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$errorMessage = ''; // Variable to store error messages
$successMessage = ''; // Variable to store success message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredFields = [
        'fullName',
        'hometown',
        'lga',
        'state',
        'nationality',
        'dateOfbirth',
        'email',
        'schools',
        'Vocational',
        'phone',
        'address',
        'MaritalStatus',
        'religion',
        'qualification'
    ];

    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $errorMessage .= "Field '$field' is missing or empty. ";
        }
    }

    // Function to validate and move an uploaded image
    function validateAndMoveImage($fileInputName, $allowedExtensions, $maxFileSize, $destinationDirectory)
    {
        $errorMsg = '';

        if (!isset($_FILES[$fileInputName]['error']) || $_FILES[$fileInputName]['error'] !== UPLOAD_ERR_OK) {
            $errorMsg = 'Failed to upload ' . $fileInputName . '. Please try again.';
        } else {
            $tempFilePath = $_FILES[$fileInputName]['tmp_name'];
            $fileExtension = pathinfo($_FILES[$fileInputName]['name'], PATHINFO_EXTENSION);
            $fileSize = $_FILES[$fileInputName]['size'];

            if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                $errorMsg = 'Invalid file format for ' . $fileInputName . '. Allowed formats: JPG, JPEG, PNG, GIF.';
            } elseif ($fileSize > $maxFileSize) {
                $errorMsg = 'File size exceeds the allowed limit (2MB) for ' . $fileInputName . '.';
            } elseif (!move_uploaded_file($tempFilePath, $destinationDirectory . $_FILES[$fileInputName]['name'])) {
                $errorMsg = 'Failed to move uploaded ' . $fileInputName . ' to the directory.';
            }
        }

        return $errorMsg;
    }

    // Image upload validation for passport
    $passportErrorMsg = validateAndMoveImage('passport', ['jpg', 'jpeg', 'png', 'gif'], 2 * 1024 * 1024, 'uploads/');

    if ($passportErrorMsg !== '') {
        $errorMessage .= $passportErrorMsg;
    }

    // Image upload validation for identification
    $identificationErrorMsg = validateAndMoveImage('Identification', ['jpg', 'jpeg', 'png', 'gif'], 2 * 1024 * 1024, 'uploads/');

    if ($identificationErrorMsg !== '') {
        $errorMessage .= $identificationErrorMsg;
    }

    if ($errorMessage === '') {
      // Generate matriculation number: U + present year + random number
      $matriculationNumber = 'U' . date('Y') . '/' . mt_rand(1000, 9999);

      // Generate exam number: 7 random numbers + 3 random letters
      $examNumber = mt_rand(1000000, 9999999) . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3);

      $stmt = $con->prepare('INSERT INTO accounts (fullName, hometown, lga, state, nationality, dateOfbirth, email, schools, Vocational, phone, address, MaritalStatus, religion, qualification, passport_image_path, identification_image_path, matriculationNumber, examNumber) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
// Assign file paths to variables
        $passportImagePath = 'uploads/' . $_FILES['passport']['name'];
        $identificationImagePath = 'uploads/' . $_FILES['Identification']['name'];

        // Bind parameters by reference
        $bindResult = $stmt->bind_param(
          'ssssssssssssssssss',
          $_POST['fullName'],
          $_POST['hometown'],
          $_POST['lga'],
          $_POST['state'],
          $_POST['nationality'],
          $_POST['dateOfbirth'],
          $_POST['email'],
          $_POST['schools'],
          $_POST['Vocational'],
          $_POST['phone'],
          $_POST['address'],
          $_POST['MaritalStatus'],
          $_POST['religion'],
          $_POST['qualification'],
          $passportImagePath,
          $identificationImagePath,
          $matriculationNumber,
          $examNumber 
      );


      if ($stmt->execute()) {
        $successMessage = 'Registration successful. We will send you an email shortly';
    } else {
        $errorMessage .= 'Registration failed, please try again';
    }

    $stmt->close();
}
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
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
           <div style="text-align: right;"><p><i class="fa fa-share-square-o"></i> grafcomas@gmail.com</p></div>
         </div>
        <div style="display: flex;flex-direction: row;">
            <span id="side"><img src="images/logo.jpg" alt="logo"  ></span>
             <span><h1>GRAFORD COLLEGE OF MARITIME & AVIATION STUDIES</h1></span>
            <span id="side" style="text-align:right;">
                <ul>
                  <li><a href="register.html">REGISTER NOW!</a></li>
                <li><a href="contact.html">CONTACT US</a></li>
                </ul>
            </span>
        </div>
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
                  
                         </ul>
                       </li>
                  <li class="nav-container">
                     <span id="hoverer">PORTAL LOGIN</span> 
                      <ul id="dropdown">
                       <li><a href="admissions.html">ADMISSION PORTAL</a></li> 
                       <li><a href="student.html">STUDENT PORTAL</a></li> 
                      </ul>
                    </li>
                    <li class="nav-container" >
                      <span id="hoverer">INFORMATION</span> 
                       <ul id="dropdown">
                         <li><a href="certificate.html">CERTIFICATE VERIFICATION</a></li> 
                        <li><a href="alumni.html">ALUMNI</a></li> 
                        <li><a href="blog.php">NEWS/BLOG</a></li>
                       </ul>
                     </li>
                  <li><a href="study.php">STUDY CENTER</a></li>
              </ul>
          </div>
    </div>
</header>
    
<aside >
        <div style="width: 20%;"><img src="images/logo.jpg" alt="logo" ></div>
        <div style="text-align: center;padding-top: 15px;color: #0e0e88;padding-left: 20px;"><h3>GRAF-COMAS</h3></div>
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
  
          </div>
        </a>
     
        <script>
          function toggleDropdown() {
            const subMenu = document.querySelector('.sub-menu1');
            subMenu.style.display = (subMenu.style.display === 'none' || subMenu.style.display === '') ? 'block' : 'none';
          }
        </script>
      <a href="admissions.html">Admission Portal</a>
      <a href="student.html">Student Portal</a>
      <a href="staff.php">Staff Portal</a>
      <a href="certificate.html">Certificate Verification</a>
      <a href="alumni.html">Alumni</a>
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

<div style="background-image: url(images/logo.jpg);background-position: center;background-repeat: no-repeat;background-size: cover;margin-top: -20px;">
 <div style="background-color: #0e0e88a8;">
  <h3 style="text-align: center;color: white;padding-top: 20px ;">Fill the form fields to register</h3>
  <?php
        if ($errorMessage !== '') {
            echo '<div style="color: white;text-align:center">' . $errorMessage . '</div>';
        }

        if ($successMessage !== '') {
            echo '<div style="color: white;text-align:center">' . $successMessage . '</div>';
        }
    ?>
  <form action="" method="post" enctype="multipart/form-data">
   
  <div style="height: auto;" id="reg">
 
  <div> 
    <input type="text" name="fullName" placeholder="Full Name"><br>
    <input type="text" name="hometown" placeholder="Home Town"><br>
    <input type="text" name="lga" id="" placeholder="LGA" ><br>
    <input type="text" name="state" placeholder="State Of Origin"><br>
    <input type="text" name="nationality" placeholder="Nationality"><br>
    <label for="">Date Of Birth</label><br>
    <input type="date" name="dateOfbirth" placeholder="Date Of Birth"><br>
    <input type="email" name="email" placeholder="Email"><br>
    <label for="">What school are you applying for</label><br>
    <select name="schools" id="">
      <option value="School Of Nautical Studies">School Of Nautical Studies</option>
      <option value="School Of Aviation">School Of Aviation</option>
      <option value="School Of Diving">School Of Diving</option>
      <option value="School Of Of Engineering">School Of Engineering</option>
      <option value="School of maritime transport & business technology">School of Maritime Transport & Business Technology</option>
      <option value="School Of Food Science">School Of Food Science</option>
      <option value="School of vocational training/rehabilitation">School of Vocational Training/Rehabilitation</option>
    </select><br>
<label for="" style="font-weight: bolder;">For school of vocational training/rehabilitation, choose your field of training <span style="color:yellow">(Note: Ignore if you are not registering for school of vocational training/Rehabilitation)</span> </label><br>
<select name="Vocational">
  <option value=" ">Select Course</option>
  <option value="Marine Captain & Quarter Master">Marine Captain & Quarter Master</option>
  <option value="Underwater Diving">Underwater Diving</option>
  <option value="Oceanography & Atmospheric Administration">Oceanography & Atmospheric Administration</option>
  <option value="Neutrical Sciences">Neutrical Sciences</option>
  <option value="HSE levels 1,2 & 3">HSE levels 1,2 & 3</option>
  <option value="NEBOSH IGC">NEBOSH IGC</option>
  <option value="Bosiet">Bosiet</option>
  <option value="Survival At Sea">Survival At Sea</option>
  <option value="First Aid">First Aid</option>
  <option value="Industrial Safty For Offshore Travel">Industrial Safty For Offshore Travel</option>
  <option value="Helicopter landing">Helicopter landing</option>
  <option value="Practical Marine Engineering">Practical Marine Engineering</option>
  <option value="Intercontinental Catering">Intercontinental Catering</option>
  <option value="Crane Operation">Crane Operation</option>
  <option value="Catapiller operation">Catapiller operation</option>
  <option value="Bulldozer operation">Bulldozer operation</option>
  <option value="Truck driving">Truck driving</option>
  <option value="Forklift Operation">Forklift Operation</option>
  <option value="Pipeline Welding">Pipeline Welding</option>
  <option value="Pipe Fitter">Pipe Fitter</option>
  <option value="Argon welding">Argon welding</option>
  <option value="Welding and Fabrication">Welding and Fabrication</option>
  <option value="Rope Access Control">Rope Access Control</option>
  <option value="Scaffolding">Scaffolding</option>
  <option value="Tug Boat Captain,Deckhand & Confined space safty">Tug Boat Captain,Deckhand & Confined space safty</option>
</select>

</div>

<div>
<input type="number" name="phone" id="" placeholder="Phone Number"><br>
<input type="text" name="address" placeholder="Home/Residential Address"><br>
<label >Marital Status:</label><br>
<select id="MaritalStatus" name="MaritalStatus"><br>
<option value="">Select status</option>
    <option value="single">Single</option>
    <option value="married">Married</option>
 </select><br>
<label >Religion:</label><br>
<select id="religion" name="religion"><br>
    <option value="christianity">Christianity</option>
    <option value="islam">Islam</option>
    <option value="hinduism">Hinduism</option>
    <option value="buddhism">Buddhism</option>
    <option value="judaism">Judaism</option>
    <option value="sikhism">Sikhism</option>
    <option value="other">Other</option>
</select><br>
<label for="">Educational Qualification</label><br>
<select name="qualification" id="">
  <option value="High School">High School</option>
  <option value="Bachelors">Bachelor's Degree</option>
  <option value="PHD">PHD</option>
  <option value="others">Others</option>
</select><br>
<label for="">Upload a Recent Passport Photograph</label><br>
<input type="file" name="passport" id=""><br>
<label for="">Upload a Valid means of Identification(NIN,PVC,Drivers License,international passport)</label><br>
<input type="file" name="Identification" id=""><br>

<button type="submits" id="pay">Proceed to payment</button>

</div>
</div>
</form>
</div></div>


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
</div>

<div>
<h3>INFORMATION CENTER</h3>
<p><a href="blog.php">News and Blog</a></p>
<p><a href="certificate.html">Certificate Verification</a></p>
<p><a href="alumni.html">Alumni page</a></p>
</div>

<div>
  <h3>CONTACT</h3>
  <p><i class="fa fa-share-square-o"></i> grafcomas@gmail.com</p>
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