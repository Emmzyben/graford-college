<?php

session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to the login page
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

$result = mysqli_query($con, 'SELECT COUNT(*) AS Accountsnum FROM accounts');
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $Accountsnum = $row['Accountsnum'];
} else {
    $Accountsnum = 0; // Handle the case where the query fails
}


$errorMessage = ''; // Variable to store error messages
$successMessage = ''; // Variable to store success message


?>



<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="images/logo.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border-bottom: 1px solid rgb(184, 180, 180);
            padding: 5px;
            text-align: left;
            font-size:15px;
        }
        img {
            max-width: 100px; /* Set the maximum width of images */
            height: auto; /* Maintain the aspect ratio */
        }
#butz{
    background-color:orangered;
    color:white;
    padding:10px;
    border: 0px solid white;
    border-radius:7px;
}
        .popup {
    position: absolute;
    top: 0;
    right:0;
    width: 80%;
    height: auto;
    justify-content: center;
    align-items: center;
    display:none;
}

.overlay {
    background-color: white;
    padding: 20px;
    text-align:center;
    font-size:15px;
}
    </style>

</head>
<body>
  <div id="show">
        <div id="side">
            <div style="display: flex;flex-direction: row;margin: 10px;">
             <span><img src="images/logo.jpg" alt="logo" width="70px" style="border-radius: 50px;"></span>
            <span style="padding-left: 10px;"><h3>GRAF-COMAS</h3></span>
            </div>
            <div style="text-align: center;"><p>Admin Tools</p></div>
            <hr>
            <ul>
                <li id="focus1"><a href="dashboard.php" style="color: #0e0e88">Dashboard</a></li>
                <li > <a href="Admission_offer.php">Offer Admission</a></li>
                <li ><a href="upload0.php" >Staff</a></li>
                <li ><a href="upload1.php">News/blog</a></li>
                <li ><a href="upload2.php">Alumni</a></li>
                <li ><a href="upload3.php">Study Center</a></li>
                    <li ><a href="admin-logout.php">Logout</a></li>
            </ul>
        </div>  
        
        <div id="aside">
        <div style="width: 22%;"><h1>hi</h1></div>
        <div style="width: 77%;">


        <div id="div1">
<div id="inner">
    <div> 
       <div id="span">
        <span id="in"></span>
       <span> <p>Total Students</p></span>
    </div>
       <div id="number">
        <h1><?php echo $Accountsnum; ?> </h1>
       </div>
    </div>

    <div>
        <div id="span">
            <span id="in" ></span>
           <span> <p></p></span>
    </div>
    <div  id="number">
        <h1></h1>
       </div>
</div>
</div>

<div style="background-color: white; margin: 40px; height: auto; width: auto; margin-top: 10px;">
    <div id="span">
        <span id="in"></span>
        <span><p>Student Summary</p></span>
    </div>

    <?php
  $query = 'SELECT id, fullName, matriculationNumber, examNumber FROM accounts';
$result = mysqli_query($con, $query);

if ($result) {
    $accounts = array();
    
    while ($row = mysqli_fetch_assoc($result)) {
        $accounts[] = $row;
    }

    // Free result set
    mysqli_free_result($result);
} else {
    $errorMessage .= 'Failed to execute query: ' . mysqli_error($con);
}



    ?>

    <!-- Display the summary table -->

    <table >
        <tr>
            <th>Full Name</th>
            <th>Matriculation Number</th>
            <th>Exam Number</th>
            <th>Action</th>
        </tr>
        <?php foreach ($accounts as $account): ?>
            <tr>
                <td><?= $account['fullName'] ?></td>
                <td><?= $account['matriculationNumber'] ?></td>
                <td><?= $account['examNumber'] ?></td>
                <td><button id='butz' onclick="showPopup('<?= $account['id'] ?>')">More Details</button></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php
   if (!empty($accounts)) {
       echo '<div id="popup" class="popup">';
       foreach ($accounts as $account) {
           echo '<div id="account-' . $account['id'] . '" class="overlay">';
           echo '<div>';
           echo '<h2>Student account Information</h2>';
   
           // Fetch all fields directly from the database for the specific account
           $accountId = $account['id'];
           $query = "SELECT * FROM accounts WHERE id = $accountId";
           $result = mysqli_query($con, $query);
   
           if ($result && $row = mysqli_fetch_assoc($result)) {
               foreach ($row as $column => $value) {
                   if ($column !== 'id') {
                       // Display all fields except the ID
                       echo '<p><strong>' . $column . ':</strong> ' . $value . '</p>';
                   }
               }
   
               // Display images
               echo '<p><strong>Passport Image:</strong> <img src="' . $row['passport_image_path'] . '" alt="Passport Image"></p>';
               echo '<p><strong>Identification Image:</strong> <img src="' . $row['identification_image_path'] . '" alt="Identification Image"></p>';
           } else {
               echo '<p>Error fetching account information.</p>';
           }
   
           echo '<button id="butz" onclick="hidePopup(' . $account['id'] . ')">Close window</button>';
           echo '</div>';
           echo '</div>';
       }
       echo '</div>';
   }
        mysqli_close($con);
   ?>
        
   

    <script>
        // JavaScript functions for showing and hiding the popup

        function showPopup(accountId) {
            document.getElementById('account-' + accountId).style.display = 'block';
            document.getElementById('popup').style.display='block';
        }
    

        function hidePopup(accountId) {
            document.getElementById('account-' + accountId).style.display = 'none';
            document.getElementById('popup').style.display='none';
        }
    </script>
</div>

</div>





        </div>
    </div>
   </div>     

<div id="noshow">
    <h1>This Page can only be accessed with a computer</h1>
    <p>Pls log in with your computer</p>
</div>
</body>
</html>