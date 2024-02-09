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

$result = mysqli_query($con, 'SELECT COUNT(*) AS num_accounts FROM staff');
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $numAccounts = $row['num_accounts'];
} else {
    $numAccounts = 0; // Handle the case where the query fails
}

$result = mysqli_query($con, 'SELECT * FROM staff');
$staffAccounts = [];

if ($result) {
    // Fetch each row as an associative array
    while ($row = mysqli_fetch_assoc($result)) {
        $staffAccounts[] = $row;
    }
} else {
    // Handle the case where the query fails
    $staffAccounts = [];
}
// Check if the values already exist in the database
function checkExistingStaff($con, $nin)
{
    $stmt = $con->prepare('SELECT COUNT(*) FROM staff WHERE  nin = ?');
    $stmt->bind_param('s',$nin);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    return $count > 0;
}

$requiredFields = [
    'staffName',
    'position',
    'address',
    'phone',
    'salary',
    'accountNumber',
    'bank',
    'nin'
];

$errors = [];
$success = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $errors[] = "Field '$field' is missing or empty. Please complete the form.";
        }
    }

    // Check if staff already exists in the database
    $staffName = $_POST['staffName'];
    $accountNumber = $_POST['accountNumber'];
    $nin = $_POST['nin'];

    if (checkExistingStaff($con,$nin)) {
        $errors[] = 'Staff with the same NIN already exists in the database.';
    }

    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $maxFileSize = 2 * 1024 * 1024; // 2 MB

        $tempFilePath = $_FILES['picture']['tmp_name'];
        $fileExtension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
        $fileSize = $_FILES['picture']['size'];

        if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
            $errors[] = 'Invalid file format. Allowed formats: JPG, JPEG, PNG, GIF.';
        } elseif ($fileSize > $maxFileSize) {
            $errors[] = 'File size exceeds the allowed limit (2MB).';
        } elseif (!move_uploaded_file($tempFilePath, 'staff_uploads/' . $_FILES['picture']['name'])) {
            $errors[] = 'Failed to move uploaded image to the directory.';
        }
    }

    if (empty($errors)) {
        $picturePath = 'staff_uploads/' . $_FILES['picture']['name'];

        $stmt = $con->prepare('INSERT INTO staff (staffName, position, address, phone, salary, accountNumber, bank, nin, picture_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $bindResult = $stmt->bind_param(
            'ssssdisss',
            $_POST['staffName'],
            $_POST['position'],
            $_POST['address'],
            $_POST['phone'],
            $_POST['salary'],
            $_POST['accountNumber'],
            $_POST['bank'],
            $_POST['nin'],
            $picturePath
        );
        

        if ($bindResult === false) {
            $errors[] = 'Binding parameters failed: ' . $stmt->error;
        }

        if ($stmt->execute()) {
            $success[] = 'Successfully posted';
        } else {
            $errors[] = 'Submission failed, please try again';
        }

        $stmt->close();
    }
}

$con->close();
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
        img {
            max-width: 100px; /* Set the maximum width of images */
            height: auto; /* Maintain the aspect ratio */
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
                <li ><a href="dashboard.php">Dashboard</a></li>
                <li > <a href="Admission_offer.php">Offer Admission</a></li>
                <li id="focus1"><a href="upload0.php" style="color: #0e0e88">Staff</a></li>
                <li ><a href="upload1.php">News/blog</a></li>
                <li ><a href="upload2.php">Alumni</a></li>
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
           <span> <p>Total Staff</p></span>
        </div>
           <div id="number">
            <h1><?php echo $numAccounts; ?> </h1>
           </div>
        </div>
     
        <div style="width: auto;">
   <form action="" method="post" enctype="multipart/form-data" id='form1'>
    <label for="">Enter Staff Details: (Note: Submitting this form will save and post this to the staff page) </label><br>
<input type="text" name="staffName" id="" placeholder="Staff Full Name"><br>
<input type="text" name="position" id="" placeholder="Position"><br>
<input type="text" name="address" id="" placeholder="Address"><br>
<input type="number" name="phone" id="" placeholder="Phone Number"><br>
<input type="text" name="salary" id="" placeholder="Salary"><br>
<input type="number" name="accountNumber" id="" placeholder="Account Number"><br>
<input type="text" name="bank" id="" placeholder="Bank Name"><br>
<input type="text" name="nin" id="" placeholder="National Identification number(NIN)"><br>
<label for="">Staff Picture</label><br>
<input type="file" name="picture" id=""><br>
<button type="submit">Submit</button>
<?php
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo '<div style="color: red;">' . htmlspecialchars($error) . '</div>';
        }
    } elseif (!empty($success)) {
        foreach ($success as $message) {
            echo '<div style="color: green;">' . htmlspecialchars($message) . '</div>';
        }
    }
    ?>


 
   </form>
    </div>
    </div>
    
    <div style="background-color: white;margin: 20px;height: 450px;width: auto;overflow:auto">
        <div id="span">
            <span id="in" ></span>
           <span> <p>Staff Summary</p></span>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Salary</th>
                <th>Account Number</th>
                <th>Bank</th>
                <th>Picture</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($staffAccounts as $account): ?>
                <tr>
                    <td><?php echo $account['staffName']; ?></td>
                    <td><?php echo $account['position']; ?></td>
                    <td><?php echo $account['address']; ?></td>
                    <td><?php echo $account['phone']; ?></td>
                    <td><?php echo $account['salary']; ?></td>
                    <td><?php echo $account['accountNumber']; ?></td>
                    <td><?php echo $account['bank']; ?></td>
                    <td>
                        <?php
                        $imagePath = $account['picture_path'];
                        if (!empty($imagePath) && file_exists($imagePath)) {
                            echo '<img src="' . $imagePath . '" alt="Staff Image">';
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