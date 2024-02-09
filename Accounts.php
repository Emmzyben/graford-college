<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
$_SESSION['last_activity'] = time();
if (!isset($_SESSION['Matnumber'])) {
    header("Location: student.php");
    exit();
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="images/logo.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Student Dashboard</title>
    <link rel="stylesheet" href="student.css">
<!-- Included Flutterwave JavaScript Library -->
<script src="https://checkout.flutterwave.com/v3.js"></script>
<script>
  function Payment() {
    FlutterwaveCheckout({
      public_key: publicKey,
      tx_ref: transactionRef,
      amount: amount,
      paymentType:paymenttype,
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
          notifyXhr.open("POST", "update.php", true);
          notifyXhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          notifyXhr.onreadystatechange = function() {
            if (notifyXhr.readyState === 4 && notifyXhr.status === 200) {
              alert("Payment Successful");
            }
          };
          notifyXhr.send("email=" + encodeURIComponent(customerEmail));
        } else {
          alert('Payment unsuccessful')
        }
      },
      onclose: function(incomplete) {
        if (incomplete === true) {
         alert('Payment unsuccessful')
         }
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
$publicKey = 'FLWPUBK_TEST-f92e874839fb45102e9c7e53e3d84695-X';
  function generateRandomString($length = 8)
        {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }
            return $randomString;
        }
// The rest of your existing code for database retrieval
$query = "SELECT id, fullName, hometown, lga, state, nationality, dateOfbirth, email, school, course, phone, address, MaritalStatus, religion, qualification, passport_image_path, identification_image_path, Fees_paid, payment_type, matriculationNumber, examNumber, created_at FROM accounts WHERE matriculationNumber = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $matnumber);
$stmt->execute();
$stmt->bind_result($id, $fullName, $hometown, $lga, $state, $nationality, $dateOfbirth, $email, $school, $course, $phone, $address, $MaritalStatus, $religion, $qualification, $passport_image_path, $identification_image_path, $Fees_paid, $payment_type, $matriculationNumber, $examNumber, $created_at);

if ($stmt->fetch()) {
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
        'Fees_paid' => $Fees_paid,
        'payment_type' => $payment_type,
        'matriculationNumber' => $matriculationNumber,
        'examNumber' => $examNumber,
        'created_at' => $created_at
    );

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Form is submitted
        // Retrieve values from the form
        $paymentType = $_POST['payment_type'];
        $amount = $_POST['amount'];


        // Generate transactionRef and escape special characters
        $transactionRef = 'txref-' . generateRandomString() . '-' . time();
        $customerName = htmlspecialchars($accounts['fullName'], ENT_QUOTES, 'UTF-8');
        $customerEmail = htmlspecialchars($accounts['email'], ENT_QUOTES, 'UTF-8');
        $phoneNumber = htmlspecialchars($accounts['phone'], ENT_QUOTES, 'UTF-8');
        $publicKey = htmlspecialchars($publicKey, ENT_QUOTES, 'UTF-8');

        // Output JavaScript script
        echo '<script>
            var customerName = "' . $customerName . '";
            var customerEmail = "' . $customerEmail . '";
            var phoneNumber = "' . $phoneNumber . '";
            var publicKey = "' . $publicKey . '";
            var amount = "' . $amount . '";
            var paymenttype = "' . $paymentType . '";
            var transactionRef = "' . $transactionRef . '";
            if (typeof Payment === "function") {
                Payment();
            } else {
                console.error("Payment function is not defined");
            }
        </script>';
    }
} else {
    $errorMessage .= 'Failed to fetch account information from the database. ';
}
$stmt->close();
?>

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
        <a href="Accounts.php" >Fees payment</a>
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
             <li ><a href="overview1.php" id="focus2">Overview</a></li>
             <li ><a href="Accounts.php" id="focus1">Fees payment</a></li>
             <li ><a href="settings.php" id="focus2">Settings</a></li>
            <li><a href="logout.php?&last_activity=' . htmlspecialchars($lastActivityTimestamp) . '" id="focus2">Logout</a></li> 
            </ul>
        </div>  
</div>
  
<div style="overflow: auto;padding: 20px;" id="role">
    <h2 style="color:white;background-color:#0e0e88;padding:20px">School fees payment portal</h2>   
    <p style="color:white;background-color:#8b8bbd;padding:20px">
   Note: For part payment,you can only pay 50% of the fees
    </p>
    <div id="pass" style="padding: 20px;">
            <h3><?php
// Your existing PHP code...

// Check if user data is available
if (!empty($accounts)) {
    ?>
    <p><strong>Course:</strong> <?php echo $accounts['course']; ?></p>

    <?php
} else {
    // Display an error message if user data is not available
    echo '<p>Error: ' . $errorMessage . '</p>';
}
?></h3>

            <h3><span style="font-weight: lighter;"> 
            <?php

// Check if user data is available
if (!empty($accounts)) {
    ?>
    <p><strong>Total fees paid: </strong> <?php echo $accounts['Fees_paid']; ?></p>

    <?php
} else {
    // Display an error message if user data is not available
    echo '<p>Error: ' . $errorMessage . '</p>';
}?>
            </span></h3>
           
            <form action="" method='post'>
                <select name="payment_type" id="">
                  <option value="">Select payment type</option>
                  <option value="Full payment">Full payment</option>
                  <option value="Half payment">Half payment</option>
                </select><br>
                <input type="number" placeholder="Enter Amount" name="amount"><br>
                <button type='submit' style="background-color: coral;padding: 7px;
            color: white;font-size: 15px;border: 0px solid black;">Proceed to payment</button>
            </form>
            
        </div>  
        <div>

        </div>
    </div>


</main>
 <script>
      if (window.location.pathname.endsWith('.html')) {
          var newUrl = window.location.pathname.slice(0, -5); // Remove .html extension
          window.history.replaceState({}, document.title, newUrl); // Change the URL without reloading the page
      }
    </script>
</body>
</html>