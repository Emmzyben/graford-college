<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
$_SESSION['last_activity'] = time();
if (!isset($_SESSION['Matnumber'])) {
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

    
} else {
    $errorMessage .= 'Failed to fetch account information from the database. ';
}
$stmt->close();
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
  function Payment(paymentType, amount) {
    // Use the PHP variables in the JavaScript code
    var publicKey = '<?php echo $publicKey; ?>';
    var transactionRef = '<?php echo generateRandomString(); ?>';
    var customerEmail = '<?php echo $accounts['email']; ?>';
    var phoneNumber = '<?php echo $accounts['phone']; ?>';
    var customerName = '<?php echo $accounts['fullName']; ?>';

    FlutterwaveCheckout({
      public_key: publicKey,
      tx_ref: transactionRef,
      amount: amount,
      paymentType: paymentType,
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
if (response.status === "successful") {
    // Call update.php upon successful payment
    var notifyXhr = new XMLHttpRequest();
    notifyXhr.open("POST", "update.php", true);
    notifyXhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    
    // Construct the data to send in the POST request
    var postData = "email=" + encodeURIComponent(customerEmail) +
                    "&amount=" + encodeURIComponent(amount) +
                    "&paymentType=" + encodeURIComponent(paymentType);

    notifyXhr.onreadystatechange = function () {
        if (notifyXhr.readyState === 4) {
            if (notifyXhr.status === 200) {
                alert("Payment Successful");
            } else {
                alert("Error updating data. Please contact support.");
            }
        }
    };
    
    // Send the POST request with the constructed data
    notifyXhr.send(postData);
} else {
    alert('Payment unsuccessful');
}

      },
      onclose: function(incomplete) {
        if (incomplete === true) {
         alert('Payment unsuccessful')
         }
      }
    });
  }
</script>

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
<script>
       function makePayment() {
        var paymentType = document.getElementById('payment_type').value;
        var amount = document.getElementById('amount').value;

        // Validate the form fields
        if (paymentType === '' || amount === '') {
            alert('Please select payment type and enter amount.');
            return false; // Prevent form submission
        }

        // Pass the values to the Payment function
        Payment(paymentType, amount);

        return false; // Prevent form submission
    }
</script>   
         <form onsubmit="return makePayment()">
    <select name="payment_type" id="payment_type">
        <option value="">Select payment type</option>
        <option value="Full payment">Full payment</option>
        <option value="Half payment">Half payment</option>
    </select><br>
    <input type="number" placeholder="Enter Amount" name="amount" id="amount"><br>
    <button type='submit' style="background-color: coral;padding: 7px;
        color: white;font-size: 15px;border: 0px solid black;" id="start-payment-button">Proceed to payment</button>
</form>
            
        </div>  
        <div>

        </div>
    </div>


</main>
</body>
</html>