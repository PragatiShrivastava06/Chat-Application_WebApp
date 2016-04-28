<?php
//This is file loads after "loginform.php" and add the user to "Users" table, 
//also it creates its own table to manage its friend list and mutual chatfilename.
//These tables are not modified if user is returning
session_start();
$uname = $_GET['uname'];
$password = $_GET['psw'];
if(($uname != "") and ($password != NULL)){

$conn = mysqli_connect("localhost:3306", "dbusername", "dbpassword", "dbname");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//Create User Table - maintains username and Login information
$sql = "CREATE TABLE users (
username VARCHAR(255) NOT NULL PRIMARY KEY,
isLogin INT(1) NULL
)";
$result = mysqli_query($conn, $sql);

if ($result === TRUE) {
    echo "Table 'users' created successfully ";
} else {
    echo "Error creating table: " . mysqli_connect_error();
}

//Add user to the users table; 
$sql = "INSERT INTO users (username)
VALUES ('$uname')";
$result = mysqli_query($conn, $sql);

if ($result === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_connect_error();
}

//Check if User is already Logged in
$sql = "SELECT isLogin FROM users WHERE username = '$uname'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

//User already logged-in
if($row["isLogin"] == "1"){ 
    header("Location: loginform.php?stat=You are already Logged in another Browser!!");
}
else if(($row["isLogin"] == "0") || ($row["isLogin"] == NULL)){
	echo "New or Returning user!!";

//Insert to Table "users" and set Login info to TRUE, also Check if this is consistenct with MySQLi
$sql = "INSERT INTO users (username, isLogin) VALUES ('$uname', 1)";

if (mysqli_query($conn, $sql))
    echo "New record created successfully";
else
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);

$sql_1 = "UPDATE users set isLogin = 1 WHERE username = '$uname'";

if (mysqli_query($conn, $sql_1))
    echo "Record UPDATED successfully";
else
    echo "Error: " . $sql_1 . "<br>" . mysqli_error($conn);
//Create personal table, same will be used to store Friends and is any coversation was made or not

$mytable_name = "mytable_" . $uname;
$mytable = "CREATE TABLE $mytable_name ( " . "my_friends VARCHAR(255) NOT NULL, " . "mutual_filename VARCHAR(100) NOT NULL, " . "primary key ( my_friends ))";

$retval = mysqli_query($conn, $mytable);
if (!$retval)
    echo "Error: Could not create table: " . $mytable . "<br>" . mysqli_error($conn);
echo "My Table created successfully\n";

setcookie("mycookie_username", $uname); //Set cookie on successful login only

header("Location: index.php? uname = $uname");
}
mysqli_close($conn);
}
else
header("Location: loginform.php?stat=Username or Password cannot be blank!!");
?>