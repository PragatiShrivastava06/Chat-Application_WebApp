<?php
//This file manages online/offline status of friends
header("content-type:application/json");
session_start();
$homeUser = $_COOKIE["mycookie_username"];
if($_POST['myName'] == $homeUser){
	//connection to the database
$conn = mysqli_connect("localhost:3306", "dbusername", "dbpassword", "dbname")
  or die("Unable to connect to MySQL ");

$myTableName = "mytable_" . $homeUser;

$sql_onlineFriends = "SELECT username from users where isLogin = 1 AND username in (SELECT my_friends from $myTableName)";
$query             = mysqli_query($conn, $sql_onlineFriends);

$outp = "[";
while($rs = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
    if ($outp != "[") {$outp .= ",";}
    $outp .= '{"User":"'  . $rs["username"] . '", "LoginStat":'  . 1 . '}';
}

$sql_offlineFriends = "SELECT username from users where isLogin = 0 AND username in (SELECT my_friends from $myTableName)";
$query             = mysqli_query($conn, $sql_offlineFriends);

while($rs = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
    if ($outp != "[") {$outp .= ",";}
    $outp .= '{"User":"'  . $rs["username"] . '", "LoginStat":'  . 0 . '}';
}

$outp .="]";

echo json_encode($outp);
}
exit();
?>