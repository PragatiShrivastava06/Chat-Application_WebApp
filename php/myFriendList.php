<?php
//This file gives list of friends(pending/accepted friend request)
header("content-type:application/json");
session_start();
$homeUser = $_COOKIE["mycookie_username"];

if($_POST['myName'] == $homeUser){
	//connection to the database
$conn = mysqli_connect("localhost:3306", "dbusername", "dbpassword", "dbname")
  or die("Unable to connect to MySQL ");

$conn = mysqli_connect("localhost:3306", "dbusername", "dbpassword", "dbname");
if (!$conn) {
die("Connection failed: " . mysqli_connect_error());
}

$myTableName = "mytable_" . $homeUser;

$sql_FriendList = "SELECT my_friends FROM $myTableName";
$query             = mysqli_query($conn, $sql_FriendList);

$outp = "[";
while($rs = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
    if ($outp != "[") {$outp .= ",";}
    $outp .= '{"User":"'  . $rs["my_friends"] . '"}';
}
$outp .="]";

echo json_encode($outp);
}
exit();
?>