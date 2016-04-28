<?php
//Mutual chat file is generated once Friend request is sent, 
//on accepting the request same mutual filename is written by friend in its own table.
//If the request is not accepted user is prompted to "check at later time" since Friend request is not yet accepted.
header("Content-Type: text/plain");
session_start();
$homeUser = $_COOKIE["mycookie_username"];
if ($_POST['myName'] == $homeUser) {
                //Insert Friend Request in FriendRequest Table
                $conn = mysqli_connect("localhost:3306", "dbusername", "dbpassword", "dbname");
                if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                }
                                $myFriend = $_POST['myFriend'];
                                $myTableName = "mytable_" . $homeUser; //My table name

                                $myFriendTableName = "mytable_" . $myFriend; //My Friends' table name
                //Now since user exist, get the common filename which is already created in Friend's personal table...chat-log***
                $sql_getMutualFileName = "SELECT mutual_filename from $myTableName where mutual_filename in (SELECT mutual_filename from $myFriendTableName)";
                $result = mysqli_query($conn, $sql_getMutualFileName);
                $rowcount = mysqli_fetch_row($result);

                if($rowcount[0] != NULL){ //Means we have found the Mututal Filename, else No filename found - Freind Request was not accepted
                $tmp_filename = $rowcount[0];
                echo $tmp_filename;
                }
                else
                echo "Fail";
mysqli_close($conn);
}
exit();
?>