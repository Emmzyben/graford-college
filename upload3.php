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
    'postTitle',
    'postContent',
    'picture',
];

$errors = [];
$success = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($requiredFields as $field) {
        if (
            ($field !== 'picture' && (!isset($_POST[$field]) || empty($_POST[$field]))) ||
            ($field === 'picture' && (!isset($_FILES['picture']) || $_FILES['picture']['error'] !== UPLOAD_ERR_OK))
        ) {
            $errors[] = "Field '$field' is missing or empty. Please complete the form.";
        }
    }

    if (empty($errors)) {
            $checkTitleQuery = $con->prepare('SELECT postID, postTitle, postContent, picture_path FROM posts WHERE postTitle = ?');
$checkTitleQuery->bind_param('s', $_POST['postTitle']);
$checkTitleQuery->execute();

// Bind the result columns to PHP variables
$checkTitleQuery->bind_result($postID, $postTitle, $postContent, $picture_path);

// Fetch the result
$checkTitleQuery->fetch();

if ($postTitle !== null) {
    $errors[] = 'A post with the same title already exists. Please choose a different title.';
}

$checkTitleQuery->close();

        if (empty($errors)) {
            // Proceed with the rest of your code to insert the new post
            $picturePath = ''; // Your existing code for handling file upload
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
                } else {
                    $picturePath = 'uploads/' . $_FILES['picture']['name'];
    
                    if (!move_uploaded_file($tempFilePath, $picturePath)) {
                        $errors[] = 'Failed to move uploaded image to the directory.';
                    }
                }
            }
    

            if (empty($errors)) {
                $stmt = $con->prepare('INSERT INTO posts (postTitle, postContent, picture_path) VALUES (?, ?, ?)');
                $bindResult = $stmt->bind_param(
                    'sss',
                    $_POST['postTitle'],
                    $_POST['postContent'],
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
                <li ><a href="upload1.php">News/blog</a></li>
                <li ><a href="upload2.php">Alumni</a></li>
                <li id="focus1"><a style="color: #0e0e88" href="upload3.php">Study Center</a></li>
                 <li ><a href="admin-logout.php">Logout</a></li>
            </ul>
        </div>  
        
        <div id="aside">
        <div style="width: 22%;"><h1>hi</h1></div>
        <div style="width: 77%;">


        <div >
    <div id="inner" style="flex-direction: column;">
        <div style="width: auto;">
          <form method="post" id='form1' action="" enctype="multipart/form-data">
          <label for="">Make a study post: </label><br>
    <input type="text" name="postTitle" placeholder="Post Title" required>
    <textarea name="postContent" placeholder="Post Content" required></textarea><br><br>
    <label for="">Attach media</label><br>
    <input type="file" name="picture" accept="image/*" required>
    <button type="submit">Submit</button>
</form>
<div>
    <div></div>

</div>
          <?php
if (!empty($errors)) {
    echo '<div style="color: red;margin:10px">';
    foreach ($errors as $error) {
        echo $error . '<br>';
    }
    echo '</div>';
}
?>


<?php
if (!empty($success)) {
    echo '<div style="color: green;margin:10px">';
    foreach ($success as $message) {
        echo $message . '<br>';
    }
    echo '</div>';
}
?>
             </form>
          </div>  
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