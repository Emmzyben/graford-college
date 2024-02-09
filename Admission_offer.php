<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit();
}
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
    #pass{
    padding: 10px;
     margin: 20px;
     height: auto;
    box-shadow: 2px 2px 10px rgb(206, 203, 203);
     color:rgb(59, 49, 49);
}
#pass>form>input{
    margin: 10px;
    border:1px solid rgb(199, 191, 191);
    padding: 5px;
}
#pass>form>label{
    margin: 10px;
}
#pass>form>button{
    margin: 10px;
    background-color: #e94d1c;
    color: #fff;
    border:0px solid rgb(199, 191, 191);
    padding: 6px;
}
        table,tr,th,td{
                border:1px solid black;
                border-collapse: collapse;
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
                <li ><a href="dashboard.php" >Dashboard</a></li>
                <li id="focus1"><a style="color: #0e0e88" href="Admission_offer.php">Offer Admission</a></li>
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

            <div id="pass">
                <h2>Offer admission</h2>
                <p>To offer a student admission, enter the student's exam number and an email will be sent to the associated student with their Matnumber and password for login</p>
               
                <form action="admit.php" method="post">
        <label for="examNumber">Enter student Exam number</label><br>
        <input type="text" id="" name="examNumber" required><br>
        <button type="submit">Admit</button>
    </form>
    
            <div id="pass">
                <h2>Deny admission</h2>
                <p>Denying a student admission deletes their information from the database and an email will be sent to them to that effect</p>
               
                <form action="deny.php" method="post">
        <label for="examNumber">Enter student Exam number</label><br>
        <input type="text" id="examNumber" name="examNumber" required><br>
        <button type="submit">Deny</button>
    </form>
    
                    
                    
            
            </div> 
      </div>
       <div style="padding:20px,text-align:center;margin:20px">
               <h2>Admission list</h2>
          <?php
    $servername = "localhost";
    $username = "grafordc_graford";
    $password = "Gratia12345";
    $dbname = "grafordc_graford";

$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select data from the admissions table
$sqlSelectAdmissions = "SELECT fullName, matriculationNumber FROM admissions";

$result = $conn->query($sqlSelectAdmissions);

// Check if the query was successful
if ($result) {
    if ($result->num_rows > 0) {
        // Output data of each row
        echo "<table >
            <tr>
                <th>Full Name</th>
                <th>Matriculation Number</th>
            </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . $row["fullName"] . "</td>
                <td>" . $row["matriculationNumber"] . "</td>
            </tr>";
        }
        echo "</table>";
    } else {
        echo "No records found in the admissions table.";
    }
} else {
    echo "Error in the select query: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
</div>
        </div></div>
        
    

<div id="noshow">
    <h1>This Page can only be accessed with a computer</h1>
    <p>Pls log in with your computer</p>
</div>
 <script>
      if (window.location.pathname.endsWith('.html')) {
          var newUrl = window.location.pathname.slice(0, -5); // Remove .html extension
          window.history.replaceState({}, document.title, newUrl); // Change the URL without reloading the page
      }
    </script>
</body>
</html>