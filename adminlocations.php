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
include_once("table_location.php");
$dbconn = new dbconn();
$tablelocation = new tablelocation();

$name = isset($_SESSION['userInfo']['name']) ? $_SESSION['userInfo']['name'] : '';

if (isset($_GET['action'])) {

    if ($_GET['action'] == 'enable') {
        $id = $_GET['locationid'];
        $isAvailable = 1;
        $sql = $tablelocation->updateLocationStatus($id, $isAvailable, $dbconn->conn);
    } elseif ($_GET['action'] == 'disable') {
        $id = $_GET['locationid'];
        $isAvailable = 0;
        $sql = $tablelocation->updateLocationStatus($id, $isAvailable, $dbconn->conn);
    } elseif ($_GET['action'] == 'delete') {
        $id = $_GET['locationid'];
        $sql = $tablelocation->deleteLocation($id, $dbconn->conn);
    }
}

if (isset($_POST['addnew'])) {

    $locationName = isset($_POST['locationName']) ? $_POST['locationName'] : '';
    $distance = isset($_POST['distance']) ? $_POST['distance'] : '';
    $addmsg = $tablelocation->addLocations($locationName, $distance, $dbconn->conn);
}

$result = $tablelocation->allLocationsAdmin($dbconn->conn);

?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/4b2ee26aaa.js" crossorigin="anonymous"></script>
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
                <a class="active " id="aUpdate">All Locations</a>
                <a class="" id="aPass">Add New Location</a>
                <a class="" href="adminaccount.php" id="accName">Welcome : <?php echo $name; ?> </a>
            </div>

            <div id="ridetable">
                <table id="ride">
                    <thead>
                        <tr>
                            <th>Location Name</th>
                            <th>Distance from Charbagh</th>
                            <th>Availability</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <?php
                        if ($result != '') {
                            $totallocation = 0;
                            while ($row = $result->fetch_assoc()) {
                                $totallocation += 1;
                        ?>
                        <tr>
                            <td><?php echo $row['name'] ?></td>
                            <td><?php echo $row['distance'] ?> Km</td>
                            <td><?php if ($row['is_available'] == 0) {
                                            echo "Not Available";
                                        } elseif ($row['is_available'] == 1) {
                                            echo "Available";
                                        } ?>
                            </td>
                            <td id="action"><?php if ($row['is_available'] == 0) : ?><a
                                    href="adminlocations.php?action=enable&locationid=<?php echo $row['id']; ?>"
                                    id="aproove">Enable</a><?php endif; ?><?php if ($row['is_available'] == 1) : ?><a
                                    href="adminlocations.php?action=disable&locationid=<?php echo $row['id']; ?>"
                                    id="cancel">Disable</a><?php endif; ?><a
                                    href="adminlocations.php?action=delete&locationid=<?php echo $row['id']; ?>"
                                    id="delete">Delete</a></td>
                        </tr>
                        <?php  } ?>
                        <tr>
                            <td colspan="4">Total locations : <?php echo $totallocation; ?></td>
                        </tr>
                        <?php } ?>
                    <tbody>
                </table>
            </div>

            <div id="loginform">
                <div id="addnew">
                    <form action="adminlocations.php" method="POST">
                        <div>
                            <h2>Add New location</h2>
                        </div>
                        <div>
                            <div><label>Location Name</label></div><small id="invalid">*Location already exists.</small>
                            <input type="text" id="lname" name="locationName" required
                                pattern="^[a-zA-Z0-9_]+( [a-zA-Z0-9_]+)*$"
                                title="Name should contain letters and letters and one space between words.">
                        </div>
                        <div>
                            <div><label>Distance From Charbagh</label></div>
                            <input type="text" class="onlytext" id="name" name="distance" required>
                        </div>
                        <div>
                            <input type="submit" id="addnewlocation" value="Add New Location" name="addnew" required>
                        </div>
                    </form>
                </div>
            </div>
            <div class="footer">

                <div id="firstFooter" class="fleft">
                    <p class="logopara">Ced<span class="logospan border-radius">Cab</span>
                </div>
                <div id="midFooter" class="fleft">
                    <p>
                        <span>&#169;</span>Copyright 2020
                    </p>
                </div>

                <div id="rightFooter" class="fleft">
                    <i class="fa fa-facebook-square icon-size mx-2 " aria-hidden="true"></i>
                    <i class="fa fa-twitter icon-size mx-2" aria-hidden="true"></i>
                    <i class="fa fa-instagram icon-size mx-2" aria-hidden="true"></i>
                </div>


                </p>
            </div>
        </div>

        <script>
        $(function() {
            $("#invalid").hide();
            $("#notification").hide();
            $("#match").hide();
            $("#addnew").hide();
            $("#aUpdate").click(function() {
                $("#invalid").hide();
                $("#aUpdate").addClass("active");
                $("#aPass").removeClass("active");
                $("#notification").hide();
                $("#addnew").hide();
                $("#ridetable").show();
            })

            $("#aPass").click(function() {
                $("#invalid").hide();
                $("#aPass").addClass("active");
                $("#aUpdate").removeClass("active");
                $("#notification").hide();
                $("#addnew").show();
                $("#ridetable").hide();
            })

            $(".onlytext").bind("keypress", function(e) {
                var keyCode = e.which ? e.which : e.keyCode

                if (!(keyCode >= 48 && keyCode <= 57)) {
                    $(".error").css("display", "inline");
                    return false;
                } else {
                    $(".error").css("display", "none");
                }
            })

            $("#lname").keyup(function() {
                $("#invalid").hide();
                var lname = $("#lname").val();
                $.ajax({
                    url: 'ajax.php',
                    type: 'POST',
                    data: {
                        lname: lname,
                        action: 'checklocation',
                    },
                    success: function(result) {
                        console.log(result);
                        if (result == "inValid") {
                            $("#invalid").show();
                        }
                    },
                    error: function() {
                        console.log("result");
                        $("#invalid").hide();
                    }
                })
            })
        })
        </script>
    </div>
</body>

</html>