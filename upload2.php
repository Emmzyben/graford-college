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

$result = mysqli_query($con, 'SELECT COUNT(*) AS num_accounts FROM alumni');
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $numAccounts = $row['num_accounts'];
} else {
    $numAccounts = 0; // Handle the case where the query fails
}

$errors = [];
$success = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST["fullName"];
    $matnumber = $_POST["matnumber"];
    $graduationYear = $_POST["graduationYear"];
    $courseOfStudy = $_POST["courseOfStudy"];

    // Generate accreditation number as "gra-comas/random number"
    $accreditationNumber = "GCS/" . rand(1000, 9999);

    // Check if a student with the same full name, matriculation number, and course of study already exists
    $checkQuery = "SELECT * FROM alumni WHERE fullName = ? AND matnumber = ? AND courseOfStudy = ?";
    $checkStmt = $con->prepare($checkQuery);

    if ($checkStmt) {
        $checkStmt->bind_param("sss", $fullName, $matnumber, $courseOfStudy);
        $checkStmt->execute();
        $checkStmt->store_result();

        // If a matching record is found, display an error
        if ($checkStmt->num_rows > 0) {
            $errors[] = "Student with the same full name, matriculation number, and course of study already exists.";
        } else {
            // Process the uploaded picture and move it to a folder
            $uploadDirectory = "uploads/"; // Adjust this path based on your setup
            $uploadedFile = $_FILES["picture"]["tmp_name"];
            $picturePath = $uploadDirectory . $_FILES["picture"]["name"];
            move_uploaded_file($uploadedFile, $picturePath);

            // Insert data into the database using prepared statement with mysqli
            $insertQuery = "INSERT INTO alumni (fullName, matnumber, graduationYear, courseOfStudy, picturePath, accreditationNumber) VALUES (?, ?, ?, ?, ?, ?)";
            $insertStmt = $con->prepare($insertQuery);

            if ($insertStmt) {
                // Bind parameters to the prepared statement
                $insertStmt->bind_param("ssssss", $fullName, $matnumber, $graduationYear, $courseOfStudy, $picturePath, $accreditationNumber);

                // Execute the prepared statement
                $insertStmt->execute();

                // Display success message or handle errors
                // You might want to redirect the user to another page after successful submission
                $success[] = "Student data has been successfully submitted.";

                // Close the statement
                $insertStmt->close();
            } else {
                // Handle the case where prepare failed
                $errors[] = "Failed to prepare the SQL statement for insertion.";
            }
        }

        // Close the statement
        $checkStmt->close();
    } else {
        // Handle the case where prepare failed for checking
        $errors[] = "Failed to prepare the SQL statement for checking.";
    }
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
      table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid rgb(184, 180, 180);
            padding: 5px;
            text-align: left;
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
                <li ><a  href="dashboard.php" >Dashboard</a></li>
                <li > <a href="Admission_offer.php">Offer Admission</a></li>
                <li ><a href="upload0.php" >Staff</a></li>
                <li ><a href="upload1.php">News/blog</a></li>
                <li id="focus1"><a style="color: #0e0e88" href="upload2.php">Alumni</a></li>
                <li ><a href="upload3.php">Study Center</a></li>
                  <li ><a href="admin-logout.php">Logout</a></li>
            </ul>
        </div>  
        
        <div id="aside">
        <div style="width: 22%;"><h1>hi</h1></div>
        <div style="width: 77%;">


        <div >
    <div id="inner" style="flex-direction: column;">
        <div style="width: auto;"> 
           <div id="span">
            <span id="in"></span>
           <span> <p>Total Alumni</p></span>
        </div>
           <div id="number">
            <h1><?php echo $numAccounts; ?> </h1>
           </div>
        </div>
     
        <div style="width: auto;">
   <form action="" method="post" enctype="multipart/form-data" id='form1' >
    <label for="">Enter Student Mat number and graduation year to add </label><br>
<input type="text" name="fullName" id="" placeholder="Enter Student Full Name"><br>
<input type="text" name="matnumber" id="" placeholder="Matriculation Number"><br>
<input type="text" name="courseOfStudy" id="" placeholder="Course Of Study"><br>
<label for="">Graduation Year</label><br>
<input type="date" name="graduationYear" id="" ><br>
<label for="">Attach Student Picture</label><br>
<input type="file" name="picture" id=""><br>
<button type="submit">Submit</button>
<?php
if (!empty($errors)) {
    echo '<div style="color: red; margin: 10px;">';
    foreach ($errors as $error) {
        echo $error . '<br>';
    }
    echo '</div>';
}

if (!empty($success)) {
    echo '<div style="color: green; margin: 10px;">';
    foreach ($success as $message) {
        echo $message . '<br>';
    }
    echo '</div>';
}
?>
   </form>
    </div>
    </div>
    
    <div style="background-color: white;margin: 20px;height: auto;width: auto;">
        <div id="span">
            <span id="in" ></span>
           <span> <p>Alumni Summary</p></span>
    </div>

<?php
$query = 'SELECT id, fullName, matnumber, graduationYear, courseOfStudy, picturePath, accreditationNumber FROM alumni';
$result = mysqli_query($con, $query);

$alumni = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $alumni[] = $row;
    }
} else {
    $errorMessage .= 'Failed to fetch alumni data from the database. ';
}
?>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Matnumber</th>
            <th>Accreditation Number</th>
            <th>Graduation Year</th>
            <th>Course of Study</th>
            <th>Picture</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($alumni as $student): ?>
            <tr>
                <td><?php echo $student['fullName']; ?></td>
                <td><?php echo $student['matnumber']; ?></td>
                <td><?php echo $student['accreditationNumber']; ?></td>
                <td><?php echo $student['graduationYear']; ?></td>
                <td><?php echo $student['courseOfStudy']; ?></td>
                <td>
                    <?php
                    $imagePath = $student['picturePath'];
                    if (!empty($imagePath) && file_exists($imagePath)) {
                        echo '<img src="' . $imagePath . '" alt="Student Image" width="200px">';
                    } else {
                        echo 'Image not found';
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

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