<?php
//This file list of all the users in the chat client system(not limited to user's friends)
header("content-type:application/json");
session_start();
$homeUser = $_COOKIE["mycookie_username"];
if($_POST['myName'] == $homeUser){
	//connection to the database
$conn = mysqli_connect("localhost:3306", "dbusername", "dbpassword", "dbname")
  or die("Unable to connect to MySQL ");

$myTableName = "mytable_" . $homeUser;

$sql_NonFriendList = "SELECT username from users where username!='$homeUser' AND username NOT in (SELECT my_friends from $myTableName)";
$query             = mysqli_query($conn, $sql_NonFriendList);

$outp = "[";
while($rs = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
    if ($outp != "[") {$outp .= ",";}
    $outp .= '{"User":"'  . $rs["username"] . '"}';
}
$outp .="]";

echo json_encode($outp);
}
exit();
?>