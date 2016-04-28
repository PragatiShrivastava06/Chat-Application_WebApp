<?php
//This file defines logic for HTML, jQuery AJAX, from here call goes to different php files in the backend i.e. completeuserlist.php, FriendRequest.php, getFileName.php, myFriendList.php, onlineFriends.php, writeToFile.php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Let's chat</title>
    <link rel="stylesheet" type="text/css" href="css-style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="jq/jquery-2.1.3.min.js"></script>
    <script src="jq/logout.js"></script>
    <style type="text/css">
    </style>
</head>

<!--Load All users in the chat client system-->
<script type="text/javascript">
$(document).ready(function(){
        $('#allUsers').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                url: "php/completeuserlist.php",
                type: "post",
                cache: false,
                data: {
                    'myName': myCookieName
                },
                success: function(data, status) {
                    $('#showAllUser').html(""); //Clear userList div on refresh
                    arr = {};
                    arr = JSON.parse(data);
                    var i;
                    for (i = 0; i < arr.length; i++) {
                        $('#showAllUser');
                        $('<button/>', {
                            id: 'cell' + i,
                            value: arr[i].User,
                            text: arr[i].User,
                            onclick: 'friendRequest(value, id, this.disabled=true)', //Add user to own friend list
                        }).appendTo('#showAllUser').css({"background": "#FF0000"});
                        $("#showAllUser").append(document.createElement("br"));
                    } // end show users
                },
            }); // end ajax call
        });

//Check Received(new/old) Friend request
$('#checkRequests').on("click", function(){
    CheckFriendRequest();
});

        $.ajax({
            url: "php/FriendRequest.php",
            type: "post",
            cache: false,
            data: { 'myName': myCookieName, 'action': 'checkRequest' },
            success : function(response, status){
                arr = JSON.parse(response);
                for(i=0; i<arr.length; i++){
                $('#receivefriendRequests').append(arr[i].User + " - Pending Friend Request").append('<br />');
                }
                setTimeout(function() { $('#receivefriendRequests').hide();}, 4000);
            }
        });

//Load all(online/offline) Friends I have on page load
$.ajax({
    url: "php/onlineFriends.php",
    type: "post",
    cache: false,
    data: {
        'myName': myCookieName
    },
    success: function(response, status) {
        arr = JSON.parse(response);
        for (i = 0; i < arr.length; i++) {
            if (arr[i].LoginStat == 1) { //This condition shows online users
                $('<button/>', {
                    id: 'cell' + i,
                    value: arr[i].User,
                    text: arr[i].User,
                    onclick: 'startChat(value, id)',
                }).appendTo('#userList').css({"color": "green"});
            } else { //This shows offline users
                $('<button/>', {
                    id: 'cell' + i,
                    value: arr[i].User,
                    text: arr[i].User,
                    onclick: 'startChat(value, id)',
                }).appendTo('#userList').css("color", "orange");
            }
            $("#userList").append(document.createElement("br"));
        }
    }
});
});

//This will get called when Homeuser add new Friend(s), which should appear in "MyFriendList"
function reloadMyFriend(){

    $("#userList").load('index.php #userList', function(){
//Load user's Friend List on page load
$.ajax({
    url: "php/onlineFriends.php",
    type: "post",
    cache: false,
    data: {
        'myName': myCookieName
    },
    success: function(response, status) {
        arr = JSON.parse(response);
        for (i = 0; i < arr.length; i++) {
            if (arr[i].LoginStat == 1) { //This condition shows online users
                $('<button/>', {
                    id: 'cell' + i,
                    value: arr[i].User,
                    text: arr[i].User,
                    onclick: 'startChat(value, id)',
                }).appendTo('#userList').css("color", "green");
            } else { //This shows offline users
                $('<button/>', {
                    id: 'cell' + i,
                    value: arr[i].User,
                    text: arr[i].User,
                    onclick: 'startChat(value, id)',
                }).appendTo('#userList').css("color", "orange");
            }
            $("#userList").append(document.createElement("br"));
        }
    }
});
    });
}

//This gets called when Home user taps Friends name from "MyFriendList"
function startChat(friend_name, button_id) {

    document.getElementById("chat").innerHTML = "You are now chatting with " + friend_name;

    var newTimerId = window.setInterval("function(){}"); //This is to clear all the old setInterval IDs
    for (var i = 0; i <= newTimerId; i++) {
        window.clearInterval(i);
    }
    delete temp; //Define and delete friend_name so that AJAX does not pick from cache
    temp = friend_name;

    $.ajax({
        url: "php/getFileName.php",
        type: "post",
        cache: true,
        data: {
            'myName': myCookieName,
            'myFriend': friend_name
        },
        success: function(data, status) {
            refreshIntervalId = setInterval(function() { loadData() }, 2000);
            onlineusers = setInterval(function() { reloadMyFriend() }, 3500);
            console.log(data + "before function call");
            function loadData() {

                if (data == 'Fail') {
                    console.log(data + "data if function call");
                    $('#chatBox').empty().append("Friend Request was not accepted - Check back later");
                } 
                else {
                    console.log(data + "data else    function call");
                    
                    $.get(("php/" + data), function(chat) {
                $('#chatBox').html(chat);
             });

                    $("#submit").click(function() { //Send chat on submit
                        sendChat();
                    });

                    $("#chat_text").keypress(function (e) { //Send chat on Enter
                            if(e.which == 13) {
                            sendChat();
                        }
                    });

                    function sendChat(){
                        if ($('#chat_text').val() != "") {
                            $.ajax({
                                url: "php/writeToFile.php",
                                type: "post",
                                cache: false,
                                async: false,
                                data: {
                                    'myName': myCookieName,
                                    'myFriend': temp,
                                    'msg': $('#chat_text').val()
                                },
                                success: function(response, status) {
                                    if (response == "ok") {
                                        $("chat_text").empty();
                                        $("#chat_text").val('');
                                        //e.preventDefault();
                                        $("chat_text").empty();
                                    }
                                }

                            });
                        }
                    }         
                }



            }
        }
    });
}

function CheckFriendRequest() {

    $.ajax({
        url: "php/FriendRequest.php",
        type: "post",
        cache: false,
        async: false,
        data: {
            'myName': myCookieName,
            'action': 'checkRequest'
        },
        success: function(response, status) {
            arr = JSON.parse(response);
            $("#receivefriendRequests").show();
            $("#receivefriendRequests").empty();
            for (i = 0; i < arr.length; i++) {
                $("#receivefriendRequests").append(arr[i].User + "Pending Friend Request").append('<br />');
            }
            $("#receivefriendRequests").fadeOut(7000);
        }
    });
}

//Freind Request logic
function friendRequest(friend, idOfButton) {
        //document.getElementById(idOfButton).disabled = 'true'; //Disable multiple clicks from user for Adding Friends
        $('#friendRequest').html("");
        $.ajax({
            url: "php/FriendRequest.php", //Send Friend request, this will appear on own page
            type: "post",
            cache: true,
            data: { 'myName': myCookieName, 'myFriend': friend, 'action': 'sendRequest' },
            success: function(data, status) {
                $('#friendRequest').html(data);
                $('#friendRequest').fadeOut(3000);
                $('#userList').append('<tr>');
                $('<button/>', {
                    value: friend,
                    text: friend,
                    disabled: false,
                }).appendTo('#userList').hide(); //Hide newly added user(s) from MyFiend List, until user Refresh's page or clicks on "refreshMyFriendList"
                reloadMyFriend();
            },
        });
        idOfButton = "";
    }

</script>

<body>

<header>
    <h2 style= "text-align:left">Welcome, <label id = "username"><?php echo $_COOKIE["mycookie_username"]; ?></label></h2>
    <div  style= "float:right; color:brown"> <button id="logout">logout</button></div>
</header>

<div id ="chat" style="margin-left:400px; color:#0000FF"></div>
<nav>
    <div id="userList">
        <button type="button" disabled style="color: green">Online</button>
        <button type="button" disabled style="color: orange">Offline</button>
        <button type="button" style="color: blue" id = "checkRequests">Check Friend Request</button>
        <h2>My Friend List</h2>
    </div>
</nav>
<article>
    <div id ="chatBox"></div>
    <div id = "hidden"></div>  
</article>

<aside>
  <button id="allUsers" style="color: blue">Click to Show(Refresh) All Users</button>
  <div id="showAllUser"></div>
</aside>

    <div class="row">
        <div class="column column-bottom-left">
            <div id="friendRequest"></div>
            <div id="receivefriendRequests" style="color: blue"></div>
        </div>
        <div class="column column-bottom-center">
            <input type="chat_text" id="chat_text" size="67" value="" placeholder="Type here">
        </div>
        <div class="column column-bottom-right">
            <button id="submit">Submit</button>
        </div>
    </div>

</body>
</html>