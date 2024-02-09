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

$requiredFields = [
    'blogPostTitle',
    'blogPost',
    'image',
];

$errors = [];
$success = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($requiredFields as $field) {
        if (
            ($field !== 'image' && (!isset($_POST[$field]) || empty($_POST[$field]))) ||
            ($field === 'image' && (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK))
        ) {
            $errors[] = "Field '$field' is missing or empty. Please complete the form.";
        }
    }

    if (empty($errors)) {
     $checkTitleQuery = $con->prepare('SELECT id, title, content, image_path FROM blogposts WHERE title = ?');
$checkTitleQuery->bind_param('s', $_POST['blogPostTitle']);
$checkTitleQuery->execute();

// Bind the result columns to PHP variables
$checkTitleQuery->bind_result($id, $title, $content, $imagePath);

// Fetch the result
$checkTitleQuery->fetch();

if ($title !== null) {
    $errors[] = 'A post with the same title already exists. Please choose a different title.';
}

$checkTitleQuery->close();


        if (empty($errors)) {
            // Proceed with the rest of your code to insert the new post
            $imagePath = ''; // Your existing code for handling file upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                $maxFileSize = 2 * 1024 * 1024; // 2 MB

                $tempFilePath = $_FILES['image']['tmp_name'];
                $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $fileSize = $_FILES['image']['size'];

                if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                    $errors[] = 'Invalid file format. Allowed formats: JPG, JPEG, PNG, GIF.';
                } elseif ($fileSize > $maxFileSize) {
                    $errors[] = 'File size exceeds the allowed limit (2MB).';
                } else {
                    $imagePath = 'uploads/' . $_FILES['image']['name'];

                    if (!move_uploaded_file($tempFilePath, $imagePath)) {
                        $errors[] = 'Failed to move uploaded image to the directory.';
                    }
                }
            }

            if (empty($errors)) {
                $stmt = $con->prepare('INSERT INTO blogposts (title, content, image_path) VALUES (?, ?, ?)');
                $bindResult = $stmt->bind_param(
                    'sss',
                    $_POST['blogPostTitle'],
                    $_POST['blogPost'],
                    $imagePath
                );

                if ($bindResult === false) {
                    $errors[] = 'Binding parameters failed: ' . $stmt->error;
                }

                if ($stmt->execute()) {
                    $success[] = 'Successfully posted';
                } else {
                    $errors[] = 'Submission failed, please try again' . $stmt->error;
                }

                $stmt->close();
            }
        }
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
                <li > <a href="Admission_offer.php">Offer Admission</a></li>
                <li ><a href="upload0.php" >Staff</a></li>
                <li id="focus1"><a style="color: #0e0e88" href="upload1.php">News/blog</a></li>
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
  <form action="" id="form1" method="post" enctype="multipart/form-data">
    <label for="Blogposttitle">Make a blog post: </label><br>
    <input type="text" name="blogPostTitle"  placeholder="Post Title"><br>
    <textarea name="blogPost"  cols="30" rows="10" placeholder="Write here"></textarea><br>
    <label for="image">Attach picture</label><br>
    <input type="file" name="image" ><br>
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
</div >
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