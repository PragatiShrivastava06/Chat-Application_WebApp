//This is jquery script for logout
str = document.cookie;
res = str.split(";");
tmp = res[0].split("=");
myCookieName = tmp[1];
$(document).ready(function() {
    $("#logout").click(function() {
        $.post("logout.php?myname=" + myCookieName, function(data) {
            document.location = "loginform.php?stat=" + 'Successfully Log out!!!';
        });
    });
});