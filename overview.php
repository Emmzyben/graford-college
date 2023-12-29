<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to the login page
    exit();
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
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
                <li id="focus1"><a style="color: #0e0e88" href="overview.php">School Overview</a></li>
                <li ><a href="upload0.php" >Staff</a></li>
                <li ><a href="upload1.php">News/blog</a></li>
                <li ><a href="upload2.php">Alumni</a></li>
                <li ><a href="upload3.php">Study Center</a></li>
            </ul>
        </div>  
        
        <div id="aside">
        <div style="width: 22%;"><h1>hi</h1></div>
        <div style="width: 77%;">


        <div >
    <div id="inner">
        <div> 
           <div id="span">
            <span id="in"></span>
           <span> <p>Search Student Data</p></span>
        </div>
           <div id="number">
            <form action="" id="search" style="margin-left: -15px;">
                <input type="search" name="search" placeholder="Enter student Mat Number">
                <button type="submit">Go!</button>
            </form>
           </div>
        </div>
    
        <div>
            <div id="span">
                <span id="in" ></span>
               <span> <p>Get information about each school</p></span>
        </div>
        <div  id="number">
           <h3>Click on each school to get the information </h3>
           </div>
    </div>
    </div>

    <div style="display: flex;flex-direction: row;">
    <div style="background-color: white;margin: 10px;height: 450px;width: 70%;">
        <div id="span">
            <span id="in" ></span>
           <span> <p>Data Summary</p></span>
    </div>
    </div>

<div style="width: 25%;background-color: white;margin: 10px;height: 450px;overflow: auto;">
<ul id="ul1">
    <li>School of Nautical Studies </li>
    <li>School of Aviation </li>
    <li>School of Diving </li>
    <li>School of Diving </li>
    <li>School of Maritime Transport & Business Technology  </li>
    <li>School of Food Science </li>

<h4 style="text-align: center;">Search for School of Vocational Training/Rehabilitation Courses</h4>

    <li>Marine Captain & Quarter Master</li>
  <li>Underwater Diving</li>
  <li >Oceanography & Atmospheric Administration</li>
  <li>Neutrical Sciences</li>
  <li >HSE levels 1,2 & 3</li>
  <li>NEBOSH IGC</li>
  <li >Bosiet</li>
  <li >Survival At Sea</li>
  <li >First Aid</li>
  <li >Industrial Safty For Offshore Travel</li>
  <li>Helicopter landing</li>
  <li >Practical Marine Engineering</li>
  <li >Intercontinental Catering</li>
  <li>Crane Operation</li>
  <li>Catapiller operation</li>
  <li >Bulldozer operation</li>
  <li>Truck driving</li>
  <li >Forklift Operation</li>
  <li >Pipeline Welding</li>
  <li >Pipe Fitter</li>
  <li>Argon welding</li>
  <li >Welding and Fabrication</li>
  <li >Rope Access Control</li>
  <li>Scaffolding</li>
  <li>Tug Boat Captain,Deckhand & Confined space safty</li>
</ul>
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