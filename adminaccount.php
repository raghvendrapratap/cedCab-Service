<?php
session_start();
if (!isset($_SESSION['userInfo'])) {
    header('Location: index.php');
} elseif ($_SESSION['userInfo']['is_admin'] == 0) {
    header('Location: index.php');
}
if (isset($_SESSION['activeTime'])) {
    if (time() - $_SESSION['activeTime'] > 300) {
        session_destroy();
        echo "<script type='text/javascript'>alert('Your Session has timed out. Please Login Again.'); window.location='index.php';</script>";
    } else {
        $_SESSION['activeTime'] = time();
    }
} else {
    $_SESSION['activeTime'] = time();
}

$message = '';
$filename = basename($_SERVER['REQUEST_URI']);
$file = explode('?', $filename);

include_once("dbconn.php");
include_once("tableRide.php");
$dbconn = new dbconn();
$tableRide = new tableRide();
$customerid = isset($_SESSION['userInfo']['customerid']) ? $_SESSION['userInfo']['customerid'] : 0;
$username = isset($_SESSION['userInfo']['username']) ? $_SESSION['userInfo']['username'] : '';
$name = isset($_SESSION['userInfo']['name']) ? $_SESSION['userInfo']['name'] : '';
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link rel="stylesheet" href="style.css">
    <title>Ced Cab</title>
</head>

<body>
    <div id="admin">

        <div class="sidebar">
            <p class="logopara">Ced<span class="logospan border-radius">Cab</span>
            </p>
            <a class="<?php if ($file[0] == "admindashboard.php") : ?> active<?php endif; ?>"
                href="admindashboard.php">Home</a>
            <a class="<?php if ($file[0] == "adminrides.php") : ?> active<?php endif; ?>"
                href="adminrides.php?status=all">Rides</a>
            <a class="<?php if ($file[0] == "adminusers.php") : ?> active<?php endif; ?>"
                href="adminusers.php">Users</a>
            <a class="<?php if ($file[0] == "adminlocations.php") : ?> active<?php endif; ?>"
                href="adminlocations.php">Locations</a>
            <a class="<?php if ($file[0] == "adminaccount.php") : ?> active<?php endif; ?>" href="adminaccount.php">Your
                Account</a>
            <a href="logout.php">Logout</a>
        </div>

        <div class="content ">
            <div class="topnav">
                <a class="active " id="aUpdate">Update Information</a>
                <a class=" " id="aPass">Change Password</a>
                <a class="" href="adminaccount.php" id="accName">Welcome : <?php echo $name; ?> </a>
            </div>

            <div id="loginform">
                <div id="notification"></div>

                <div id="userInfo">
                    <form action="login.php" method="POST">
                        <div>
                            <h2>Your Info</h2>
                        </div>
                        <div>
                            <div><label>Username</label></div>
                            <input type="text" readonly id="username" name="username">
                        </div>
                        <div>
                            <div><label>Name</label></div>
                            <input type="text" id="name" name="name">
                        </div>
                        <div>
                            <div><label>Mob</label></div>
                            <input type="text" id="mob" name="mob">
                        </div>
                        <div>
                            <input type="button" id="updateInfo" value="Update Info" name="update">
                        </div>
                    </form>
                </div>

                <div id="change">
                    <form action="login.php" method="POST">
                        <div>
                            <h2>Change Password</h2>
                        </div>
                        <div>
                            <div><label>Old Password</label></div>
                            <input type="password" id="oldpass" name="username" value="">
                        </div>
                        <div>
                            <div><label>New Password</label></div>
                            <input type="password" id="newpass" name="newpass" value="">
                        </div>
                        <div>
                            <div><label>Re-Type Password</label></div>
                            <input type="password" id="renewpass" name="renewpass" value="">
                            <small id="match">*Password doesn't match</small>
                        </div>
                        <div>
                            <input type="button" id="changepass" value="Change Password" name="update">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
        $(function() {
            $("#notification").hide();
            $("#match").hide();
            $("#change").hide();
            $("#aUpdate").click(function() {
                $("#aUpdate").addClass("active");
                $("#aPass").removeClass("active");
                $("#notification").hide();
                $("#change").hide();
                $("#userInfo").show();
            })

            $("#aPass").click(function() {
                $("#aPass").addClass("active");
                $("#aUpdate").removeClass("active");
                $("#notification").hide();
                $("#change").show();
                $("#userInfo").hide();
            })

            var customerid = '<?php echo $customerid; ?>';
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                data: {
                    customerid: customerid,
                    action: 'getUserInfo',
                },
                dataType: "json",
                success: function(result) {
                    $("#username").val(result["user_name"]);
                    $("#name").val(result["name"]);
                    $("#mob").val(result["mobile"]);
                },
                error: function() {
                    console.log("Error");
                }
            })

            $("#updateInfo").click(function() {
                $("#notification").hide();
                var username = $("#username").val();
                var name = $("#name").val();
                var mobile = $("#mob").val();
                $.ajax({
                    url: 'ajax.php',
                    type: 'POST',
                    data: {
                        customerid: customerid,
                        username: username,
                        name: name,
                        mobile: mobile,
                        action: 'updateUser',
                    },
                    // dataType:"json",          
                    success: function(result) {
                        $("#notification").html(result);
                        $("#notification").show();
                    },
                    error: function() {
                        $("#notification").html("Error");
                        $("#notification").show();
                    }
                })
            })

            $("#changepass").click(function() {

                var oldpass = $("#oldpass").val();
                var newpass = $("#newpass").val();
                var renewpass = $("#renewpass").val();

                if (oldpass != '' && newpass != '' && renewpass != '') {

                    $("#notification").hide();
                    if (newpass == renewpass) {
                        $("#match").hide();

                        $.ajax({
                            url: 'ajax.php',
                            type: 'POST',
                            data: {
                                customerid: customerid,
                                oldpass: oldpass,
                                newpass: newpass,
                                action: 'changePass',
                            },
                            // dataType:"json",          
                            success: function(result) {
                                $("#notification").html(result);
                                $("#notification").show();
                            },
                            error: function() {
                                $("#notification").html("Error");
                                $("#notification").show();
                            }
                        })

                    } else {
                        $("#match").show();
                    }

                } else {
                    $("#notification").html("*Enter valid inputs.");
                    $("#notification").show();
                }

            })
        })
        </script>
    </div>
</body>

</html>