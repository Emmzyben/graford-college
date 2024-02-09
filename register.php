<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="images/logo.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="about.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
<!-- Included Flutterwave JavaScript Library -->
<script src="https://checkout.flutterwave.com/v3.js"></script>
<script>
  function makePayment() {
    FlutterwaveCheckout({
      public_key: publicKey,
      tx_ref: transactionRef,
      amount: 25000,
      currency: "NGN",
      payment_options: "card, banktransfer, ussd",
      meta: {
        source: "docs-inline-test",
        consumer_mac: "92a3-912ba-1192a",
      },
      customer: {
        email: customerEmail,
        phone_number: phoneNumber,
        name: customerName,
      },
      customizations: {
        title: "Graford college",
        description: "One-time registration fee",
        logo: "https://checkout.flutterwave.com/assets/img/rave-logo.png",
      },
      callback: function(response) {
        // Check if payment is successful
        if (response.status === "successful") {
          // Call notify.php upon successful payment
          var notifyXhr = new XMLHttpRequest();
          notifyXhr.open("POST", "notify.php", true);
          notifyXhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          notifyXhr.onreadystatechange = function() {
            if (notifyXhr.readyState === 4 && notifyXhr.status === 200) {
              alert("Registration Successful");
            }
          };
          notifyXhr.send("email=" + encodeURIComponent(customerEmail));
        } else {
          // Payment unsuccessful, call delete.php
          var deleteXhr = new XMLHttpRequest();
          deleteXhr.open("POST", "delete.php", true);
          deleteXhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          deleteXhr.onreadystatechange = function() {
            if (deleteXhr.readyState === 4 && deleteXhr.status === 200) {
              alert("Registration Failed");
            }
          };
          deleteXhr.send("email=" + encodeURIComponent(customerEmail));
        }
      },
      onclose: function(incomplete) {
        if (incomplete === true) {
          // Payment incomplete, call delete.php
          var deleteXhr = new XMLHttpRequest();
          deleteXhr.open("POST", "delete.php", true);
          deleteXhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          deleteXhr.onreadystatechange = function() {
            if (deleteXhr.readyState === 4 && deleteXhr.status === 200) {
              alert("Registration Failed");
            }
          };
          deleteXhr.send("email=" + encodeURIComponent(customerEmail));
        }
      }
    });
  }
</script>


<style>
  .course-info {
    display: none;
    color: red;
  }
</style>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
$publicKey = 'FLWPUBK_TEST-f92e874839fb45102e9c7e53e3d84695-X';

 // Function to generate a random string
        function generateRandomString($length = 8)
        {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }
            return $randomString;
        }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredFields = [
        'fullName',
        'hometown',
        'lga',
        'state',
        'nationality',
        'dateOfbirth',
        'email',
        'school',
        'course',
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

    $checkExistingQuery = $con->prepare('SELECT COUNT(*) FROM accounts WHERE fullName = ? AND email = ?');
    $checkExistingQuery->bind_param('ss', $_POST['fullName'], $_POST['email']);
    $checkExistingQuery->execute();
    $checkExistingQuery->bind_result($existingAccountsCount);
    $checkExistingQuery->fetch();
    $checkExistingQuery->close();

    if ($existingAccountsCount > 0) {
        $errorMessage .= "An account with the same name and email already exists.";
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

        $randomPassword = mt_rand(10000000, 99999999); // Generate 8-digit random number

        // Generate exam number: 7 random numbers + 3 random letters
        $examNumber = mt_rand(1000000, 9999999) . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3);

        $stmt = $con->prepare('INSERT INTO accounts (fullName, hometown, lga, state, nationality, dateOfbirth, email, school, course, phone, address, MaritalStatus, religion, qualification, password, passport_image_path, identification_image_path, matriculationNumber, examNumber) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

        // Assign file paths to variables
        $passportImagePath = 'uploads/' . $_FILES['passport']['name'];
        $identificationImagePath = 'uploads/' . $_FILES['Identification']['name'];

        // Bind parameters by reference
        $bindResult = $stmt->bind_param(
            'sssssssssssssssssss',
            $_POST['fullName'],
            $_POST['hometown'],
            $_POST['lga'],
            $_POST['state'],
            $_POST['nationality'],
            $_POST['dateOfbirth'],
            $_POST['email'],
            $_POST['school'],
            $_POST['course'],
            $_POST['phone'],
            $_POST['address'],
            $_POST['MaritalStatus'],
            $_POST['religion'],
            $_POST['qualification'],
            $randomPassword,
            $passportImagePath,
            $identificationImagePath,
            $matriculationNumber,
            $examNumber
        );

       

        if ($stmt->execute()) {
            $transactionRef = 'txref-' . generateRandomString() . '-' . time();
            // Escape special characters to prevent potential issues in JavaScript
            $customerName = htmlspecialchars($_POST['fullName'], ENT_QUOTES, 'UTF-8');
            $customerEmail = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
            $phoneNumber = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
            $publicKey = htmlspecialchars($publicKey, ENT_QUOTES, 'UTF-8');

          
           echo '<script>
            var customerName = "' . $customerName . '";
            var customerEmail = "' . $customerEmail . '";
            var phoneNumber = "' . $phoneNumber . '";
            var publicKey = "' . $publicKey . '";
            var transactionRef = "' . $transactionRef . '";
            if (typeof makePayment === "function") {
                makePayment();
            } else {
                console.error("makePayment function is not defined");
            }
          </script>';
} else {
    $errorMessage .= 'Registration failed, please try again';
}

        $stmt->close();
    }
}
?>

</head>
<body>
<header id="cover" >
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
    <select name="school" id="schoolSelect">
      <option value="">Select School</option>
      <option value="School Of Nautical Studies">School Of Nautical Studies</option>
      <option value="School Of Aviation">School Of Aviation</option>
      <option value="School Of Diving">School Of Diving</option>
      <option value="School Of Engineering">School Of Engineering</option>
      <option value="School Of Maritime Transport">School of Maritime Transport & Business Technology</option>
      <option value="School Of Food Science">School Of Food Science</option>
      <option value="School Of Vocational Training">School of Vocational Training/Rehabilitation</option>
      <option value="School Of Agriculture">School of Agriculture</option>
      <option value="School Of Sciences">School of Sciences</option>
    </select>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var selectElement = document.getElementById('schoolSelect');
        var courseDivs = document.querySelectorAll('.course-info');
      
        selectElement.addEventListener('change', function() {
          var selectedValue = selectElement.value;
          
          // Hide all course divs
          courseDivs.forEach(function(div) {
            div.style.display = 'none';
          });
      
          // Show the selected course div
          var selectedDiv = document.getElementById(selectedValue);
          if (selectedDiv) {
            selectedDiv.style.display = 'block';
          }
        });
      });
      </script>
    <br>
<label for="" style="font-weight: bolder;">Course </label><br>
<div id="School Of Nautical Studies" class="course-info">
<select id="select1" onchange="updateField(this.value, 'field1')">
    <option value="">Select course</option>
    <option value="Nautical Science">Nautical Science</option>
  </select>
</div>
<div id="School Of Aviation" class="course-info">
 <select id="select2" onchange="updateField(this.value, 'field2')">
    <option value="">Select course</option>
      <option value="Commercial Pilot Program">Commercial Pilot Program</option>
      <option value="Private Pilot Program">Private Pilot Program</option>
      <option value="Private Airplane Pilot License (PAPL)">Private Airplane Pilot License (PAPL)</option>
      <option value="Commercial Airplane Pilot License (CAPL)">Commercial Airplane Pilot License (CAPL)</option>
      <option value="Commercial Helicopter Pilot License (CHPL)">Commercial Helicopter Pilot License (CHPL)</option>
      <option value="Private Helicopter Pilot License (PHPL)">Private Helicopter Pilot License (PHPL)</option>
      <option value="Flight Dispatcher Course (FDC)">Flight Dispatcher Course (FDC)</option>
      <option value="Instrument Ratings">Instrument Ratings</option>
      <option value="Multi-Engine Rating">Multi-Engine Rating</option>
      <option value="Night Rating">Night Rating</option>
      <option value="Air Ticketing/Reservation (ATR)">Air Ticketing/Reservation (ATR)</option>
      <option value="Basic Flight Dispatcher/Ground Pilot">Basic Flight Dispatcher/Ground Pilot</option>
      <option value="Advance Flight Dispatcher/Ground Pilot">Advance Flight Dispatcher/Ground Pilot</option>
      <option value="Flight Attendant/Cabin Crew Management">Flight Attendant/Cabin Crew Management</option>
      <option value="Helicopter Landing Officer (HLO)">Helicopter Landing Officer (HLO)</option>
      <option value="Aviation and Airport Management">Aviation and Airport Management</option>
      <option value="Customer Service Management">Customer Service Management</option>
      <option value="Travel Agency Management (TAM)">Travel Agency Management (TAM)</option>
      <option value="Hotel and Hospitality Management">Hotel and Hospitality Management</option>
      <option value="Aviation Security Administration">Aviation Security Administration</option>
    </select>

</div>
<div id="School Of Diving" class="course-info">
 <select id="select3" onchange="updateField(this.value, 'field3')">
    <option value="">Select course</option>
      <option value="Underwater Diving">Underwater Diving</option>
      <option value="Underwater Welding">Underwater Welding</option>
      <option value="Underwater Inspection">Underwater Inspection</option>
      <option value="Underwater Technician">Underwater Technician</option>
      <option value="Underwater Architecture">Underwater Architecture</option>
      <option value="Underwater Videography">Underwater Videography</option>
      <option value="Underwater Photography">Underwater Photography</option>
      <option value="Underwater Operator Training">Underwater Operator Training</option>
      <option value="Master Scuba Diver">Master Scuba Diver</option>
      <option value="Boat Diver">Boat Diver</option>
      <option value="Cavern Diver">Cavern Diver</option>
      <option value="Coral Reef Conservation">Coral Reef Conservation</option>
      <option value="Deep Diver">Deep Diver</option>
      <option value="Dive Propulsion Vehicle">Dive Propulsion Vehicle</option>
      <option value="Drift Diver">Drift Diver</option>
      <option value="Enriched Air Nitrox">Enriched Air Nitrox</option>
      <option value="Equipment Specialist">Equipment Specialist</option>
      <option value="Fish Identification">Fish Identification</option>
      <option value="Multilevel & Dive Computer">Multilevel & Dive Computer</option>
      <option value="Night Diver">Night Diver</option>
      <option value="Peak Performance Buoyancy">Peak Performance Buoyancy</option>
      <option value="Project Aware">Project Aware</option>
      <option value="Emergency First Response">Emergency First Response</option>
      <option value="Rescue Diver">Rescue Diver</option>
      <option value="Dive Master">Dive Master</option>
      <option value="Underwater Naturalisat">Underwater Naturalisat</option>
      <option value="Underwater Navigator">Underwater Navigator</option>
      <option value="Underwater Search and Recovery">Underwater Search and Recovery</option>
      <option value="Commercial Diving">Commercial Diving</option>
      <option value="Wreck Diver">Wreck Diver</option>
    </select>
</div>

<div id="School Of Engineering" class="course-info">
         <select id="select4" onchange="updateField(this.value, 'field4')">
    <option value="">Select course</option>
    <option value="Marine Engineering">Marine engineering</option>
    <option value="Civil Engineering">Civil engineering</option>
    <option value="Petroleum engineering">Petroleum engineering</option>
    <option value="Petroleum and gas engineering">Petroleum and gas engineering</option>
    <option value="Petrochemical Engineering">Petrochemical Engineering</option>
    <option value="Chemical engineering">Chemical engineering</option>
    <option value="Mechanical engineering">Mechanical engineering</option>
    <option value="Electrical Engineering">Electrical Engineering</option>
    <option value="Computer Engineering">Computer Engineering</option>
  </select>
</div>
<div id="School Of Maritime Transport" class="course-info">
<select id="select5" onchange="updateField(this.value, 'field5')">
  <option value="">Select Course</option>
 <option value="Business Logistics">Business Logistics</option> 
<option value="Maritime Law">Maritime Law</option>
<option value="Maritime management">Maritime management</option>
<option value="Marine Insurance">Marine Insurance</option>
<option value="Ports & Logistic Management">Ports & Logistic Management</option>
<option value="Maritime Consultant">Maritime Consultant</option>
<option value="Maritime Economics">Maritime Economics</option>
<option value="Ship Chartering">Ship Chartering</option>
<option value="Ship Operations">Ship Operations</option>
<option value="Shipbroker">Shipbroker</option>
<option value="Shipping Marketing">Shipping Marketing</option>
<option value="Maritime Safety Management">Maritime Safety Management</option>
<option value="Maritime Telecommunications">Maritime Telecommunications</option>
<option value="Assistant Underwater Marine">Assistant Underwater Marine</option>
<option value="Economic Globalization & Shipping">Economic Globalization & Shipping</option>
<option value="Maritime Business Administration">Maritime Business Administration</option>
<option value="Hydrology">Hydrology</option>
<option value="Nautical Science">Nautical Science</option>
<option value="Marine Economics and Finance">Marine Economics and Finance</option>
<option value="Ports Management">Ports Management</option>
<option value="Transport and Logistics Management">Transport and Logistics Management</option>
<option value="Weast Environment">Weast Environment</option>
<option value="Marine Management and Pollution Control">Marine Management and Pollution Control</option>
<option value="Marine Geology">Marine Geology</option>
<option value="Meterology and Climate Change">Meterology and Climate Change</option>
</select>
</div>
<div id="School Of Food Science" class="course-info">
          <select id="select6" onchange="updateField(this.value, 'field6')">
    <option value="">Select course</option>
  <option value="Food & Nutrition">Food & Nutrition</option>  
<option value="Food Safety & Hygiene">Food Safety & Hygiene</option>
<option value="Food Science & Technology">Food Science & Technology</option>
<option value="Intercontinental Catering">Intercontinental Catering</option>
<option value="Backry & Winery">Backry & Winery</option>
<option value="Hotel & Management">Hotel & Management</option>
<option value="Tourism & Hospitality Management">Tourism & Hospitality Management</option>
<option value="Human Resource Management">Human Resource Management</option>
<option value="Marketing">Marketing</option>
<option value="Businesses Administration">Businesses Administration</option>
<option value="Public Administration">Public Administration</option>
<option value="Secretariat Administration">Secretariat Administration</option>
<option value="Accounting">Accounting</option>
<option value="ICT">ICT</option>
  </select>
</div>
<div id="School Of Vocational Training" class="course-info">
          <select id="select7" onchange="updateField(this.value, 'field7')">
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
<div id="School Of Agriculture" class="course-info">
          <select id="select8" onchange="updateField(this.value, 'field8')">
    <option value="">Select Course</option>
    <option value="Fisheries Technology">Fisheries Technology</option>
    <option value="Oceanography and Fishery Science">Oceanography and Fishery Science</option>
    <option value="Fisheries and Aquaculture">	Fisheries and Aquaculture</option>
  </select>
</div>
<div id="School Of Sciences" class="course-info">
          <select id="select9" onchange="updateField(this.value, 'field9')">
    <option value="">Select Course</option>
    <option value=" Computer Science"> Computer Science</option>
    <option value="Science Laboratory">Science Laboratory</option>
    <option value="Oceanography and Fishery Science">Oceanography and Fishery Science</option>
  </select>
</div>
</div>

<div>
<input type="hidden" id="targetField" name="course" value="">
    <script>
        function updateField(value, fieldName) {
            document.getElementById('targetField').value = value;
        }
    </script>
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
<p>
By proceeding you agree to pay a non-refundable fee of #25,000 only for Registration
</p>
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


<script src="select.js"></script>
    <script src="javascript.js"></script>
 <script>
      if (window.location.pathname.endsWith('.html')) {
          var newUrl = window.location.pathname.slice(0, -5); // Remove .html extension
          window.history.replaceState({}, document.title, newUrl); // Change the URL without reloading the page
      }
    </script>
</body>
</html>