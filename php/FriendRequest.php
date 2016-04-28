<?php
//This file SEND Friend Reuqest and also keep track if friend request is already sent in the past, 
//if yes it does not sent a new one
session_start();
$homeUser = $_COOKIE["mycookie_username"];
if ($_POST['myName'] == $homeUser) {

//Insert Friend Request in FriendRequest Table
$conn = mysqli_connect("localhost:3306", "dbusername", "dbpassword", "dbname");
if (!$conn) {
die("Connection failed: " . mysqli_connect_error());
}
// Send Friend request
if ($_POST['action'] == "sendRequest") {
                                $myFriend = $_POST['myFriend'];
                                $myTableName = "mytable_" . $homeUser; //Home user table name
                                $myFriendTableName = "mytable_" . $myFriend; //My Friends' table name

                //Before sending(adding) request back, check if similar entry exist, if so delte that and start chatting...
                                $sql_findFriendReq = "SELECT * FROM FriendRequest WHERE user_to = '$homeUser' AND user_from = '$myFriend'";
                                $query             = mysqli_query($conn, $sql_findFriendReq); //Friend Request already available
                                $row               = mysqli_num_rows($query);
                                if ($row == 1) {
                                                $sql_delete = "DELETE FROM FriendRequest WHERE user_to = '$homeUser' AND user_from = '$myFriend'";
                                                mysqli_query($conn, $sql_delete);
                                                
//Now since user exist, get the common filename which is already created in Friend's personal table...chat-log***
$sql_getMutualFileName = "SELECT mutual_filename FROM $myFriendTableName WHERE my_friends = '$homeUser' ";
                                //$result_FileName = mysqli_query($conn, $sql_getMutualFileName);
                                $result_FileName = mysqli_fetch_row(mysqli_query($conn, $sql_getMutualFileName));
                                $tmp_filename = $result_FileName[0];

                //Insert fetched filename in own table
                                $sql_friend_filename = "INSERT INTO $myTableName (my_friends, mutual_filename) VALUES ('$myFriend', '$tmp_filename')";
                                mysqli_query($conn, $sql_friend_filename);
                                }
                                
                                else {
                                        $sql_1 = "INSERT INTO FriendRequest (user_from, user_to) VALUES ('$homeUser', '$myFriend')";
                                        mysqli_query($conn, $sql_1);
                                        echo "Friend request sent " . $myFriend;

                //Add Friend to own table and create chatlog file(user will get message message sent until Friend Request acepted will NOT be delivered).
                                        $tmpfname = "chat-log".mt_rand();
                                        $myfile = fopen("$tmpfname", "w") or die("Unable to open file!");
                                        fclose($myfile);                                        

                //INSERT above created file name in Homeuser table
                                        $sql_myfilename      = "INSERT INTO $myTableName (my_friends, mutual_filename) VALUES ('$myFriend', '$tmpfname')";
                                        mysqli_query($conn, $sql_myfilename);
                                }
                }
                
                //Fetch How many Firends requests I have
                if ($_POST['action'] == "checkRequest") {
                                $sql_2  = "SELECT user_from FROM FriendRequest WHERE user_to = '$homeUser' ";
                                $result = mysqli_query($conn, $sql_2);
                                
                                header("content-type:application/json");
                                
                                $outp = "[";
                                while ($rs = mysqli_fetch_assoc($result)) {
                                                if ($outp != "[") {
                                                                $outp .= ",";
                                                }
                                                $outp .= '{"User":"' . $rs["user_from"] . '"}';
                                }
                                $outp .= "]";
                                echo json_encode($outp);
                                
                }
}
exit();
?>