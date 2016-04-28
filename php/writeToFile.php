<?php
//This file is called when friend request is accepted and user starts sending data, 
//here logic is to get mutual filename and write chat to it
header("Content-Type: text/plain");
session_start();
$homeUser = $_COOKIE["mycookie_username"];
$rcvd_data = $_POST['msg'];
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
//Add Friend to own table and create chatlog file(user will get message message sent until Friend Request acepted will NOT be delivered).
        $myfile = fopen("$tmp_filename", "a") or die("Unable to open file!");
        fwrite($myfile, "<br>" . "(" . $homeUser . ")" . " " . date("H:i:s") . " - " . $rcvd_data . PHP_EOL);
        fclose($myfile);
        echo "ok";
        }
        else
        echo "Fail";
                  
mysqli_close($conn);
}
exit();
?>