<?php
//This file is called on logout
session_start();
ini_set('display_errors', 1);
header("Content-Type: application/json", true);
$uname = $_COOKIE["mycookie_username"];

//connection to the database
$conn = mysqli_connect("localhost:3306", "dbusername", "dbpassword", "dbname") or die("Unable to connect to MySQL ");
//echo "Connected to MySQL ";

$sql = "UPDATE users set isLogin = 0 WHERE username = '$uname'";

mysqli_query($conn, $sql);

header("Location: loginform.php?stat='Successfully Log out!!!'");
mysqli_close($conn);

?>