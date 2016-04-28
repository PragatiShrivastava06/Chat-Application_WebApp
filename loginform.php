<?php
//This file is for login page, where user needs to enter username and password
if($_GET['stat']){ //Checking if user is already logged in
echo $_GET['stat'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login Form</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="css-style.css" rel="stylesheet" type="text/css" />

</head>

<body>
    <div class="head">
        <img src="user.png" alt="" />
    </div>


    <div class="login-body">
        <legend>Welcome Let's Chat</legend>

        <form action="insert.php" method="get" accept-charset="UTF-8">
            <input type="text" name="uname" placeholder="Username">
            <br>
            <input type="password" name="psw" placeholder="Password">
            <input type="submit" value="Submit">
        </form>

    </div>

</body>
</html>