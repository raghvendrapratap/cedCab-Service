<?php

session_start();
if (!isset($_SESSION['userInfo'])) {
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

$filename = basename($_SERVER['REQUEST_URI']);
$file = explode('?', $filename);

include_once("dbconn.php");
include_once("tableRide.php");
include_once("users.php");

$dbconn = new dbconn();
$tableRide = new tableRide();
$user = new user();

$customerid = isset($_SESSION['userInfo']['customerid']) ? $_SESSION['userInfo']['customerid'] : 0;
$name = isset($_SESSION['userInfo']['name']) ? $_SESSION['userInfo']['name'] : '';

if (isset($_GET['action'])) {
    $rideid = $_GET['rideid'];
    $userid = $_GET['userid'];

    $rideInfo = $tableRide->rideInfo($rideid, $dbconn->conn);
    $userInfo = $user->getUserInfo($userid, $dbconn->conn);
}
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

    <?php if ($_SESSION['userInfo']['is_admin'] == 1) { ?>

    <div id="admin">
        <div class="sidebar">
            <p class="logopara">Ced<span class="logospan border-radius">Cab</span>
            </p>
            <a class="" href="admindashboard.php">Home</a>
            <a class="" href="adminrides.php?status=all">Rides</a>
            <a class="" href="adminusers.php">Users</a>
            <a class="" href="adminlocations.php">Locations</a>
            <a class="" href="adminaccount.php">Your Account</a>
            <a href="logout.php">Logout</a>
        </div>
        <div class="content ">
            <div class="topnav">
                <a class="" href="adminaccount.php" id="accName">Welcome : <?php echo $name; ?> </a>
            </div>
            <?php } ?>

            <?php if ($_SESSION['userInfo']['is_admin'] == 0) { ?>
            <div id="customer">
                <div class="sidebar">

                    <p class="logopara">Ced<span class="logospan border-radius">Cab</span>
                    </p>
                    </p>
                    <a class="<?php if ($file[0] == "customerdashboard.php") : ?> active<?php endif; ?>"
                        href="customerdashboard.php">Home</a>
                    <a class="<?php if ($file[0] == "yourride.php") : ?> active<?php endif; ?>" href="yourride.php">Your
                        Ride</a>
                    <a class="<?php if ($file[0] == "yourprofile.php") : ?> active<?php endif; ?>"
                        href="yourprofile.php">Your
                        Profile</a>
                    <a href="index.php">Book Cab</a>
                    <a href="logout.php">Logout</a>
                </div>

                <div class="content ">
                    <div class="topnav">
                        <a class="" href="yourprofile.php" id="accName">Welcome :
                            <?php echo $_SESSION['userInfo']['name']; ?>
                        </a>
                    </div>
                    <?php } ?>
                    <div id="invoice">
                        <div id="printdiv">

                            <?php
                            if (isset($rideInfo) && isset($userInfo)) {
                            ?>

                            <table id="tinvoice">
                                <tr>
                                    <td id="thead">
                                        <p class="logopara">Ced<span class="logospan border-radius">Cab</span></p>
                                    </td>
                                    <td id="tdate">Date: <?php echo $rideInfo['ride_date']; ?></td>
                                </tr>
                                <tr>
                                    <th id="tinvce" colspan="2">Invoice</th>
                                </tr>
                                <tr></tr>

                                <tr>
                                    <td class="tdc1">Customer Name :</td>
                                    <td class="tdc2"><?php echo $userInfo['name']; ?></td>
                                </tr>
                                <tr>
                                    <td class="tdc1">Ride ID :</td>
                                    <td class="tdc2"><?php echo $rideInfo['ride_id']; ?></td>
                                </tr>
                                <tr>
                                    <td class="tdc1">Pickup Point :</td>
                                    <td class="tdc2"><?php echo $rideInfo['from_distance']; ?></td>
                                </tr>
                                <tr>
                                    <td class="tdc1">Destination Point :</td>
                                    <td class="tdc2"><?php echo $rideInfo['to_distance']; ?></td>
                                </tr>
                                <tr>
                                    <td class="tdc1">Cab Type :</td>
                                    <td class="tdc2"><?php echo $rideInfo['cabType']; ?></td>
                                </tr>
                                <tr>
                                    <td class="tdc1">Total luggage :</td>
                                    <td class="tdc2"><?php echo $rideInfo['luggage']; ?> Kg.</td>
                                </tr>
                                <tr>
                                    <td class="tdc1">Total Distance Travelled :</td>
                                    <td class="tdc2"><?php echo $rideInfo['total_distance']; ?> Km.</td>
                                </tr>
                                <tr>
                                    <td class="tdc1"> Total Fare :</td>
                                    <td class="tdc2"><strong>Rs. <?php echo $rideInfo['total_fare']; ?></strong></td>
                                </tr>
                                <tr>
                                    <td class="tdc1"> Status :</td>
                                    <td class="tdc2"><strong><?php if ($rideInfo['status'] == 0) {
                                                                        echo "Cancelled";
                                                                    } elseif ($rideInfo['status'] == 1) {
                                                                        echo "Pending";
                                                                    } elseif ($rideInfo['status'] == 2) {
                                                                        echo "Completed";
                                                                    }; ?></strong></td>
                                </tr>
                            </table>
                        </div>
                        <p class="">
                            <button id="print">Print</button>
                        </p>
                        <?php
                            } ?>
                    </div>
                </div>
            </div>
            <script>
            $(function() {
                $("button").click(function() {

                    printDiv("printdiv");

                    function printDiv(id) {
                        var printContents = document.getElementById(id).innerHTML;
                        var originalContents = document.body.innerHTML;
                        document.body.innerHTML = printContents;
                        window.print();
                        location.reload();
                    }
                })
            })
            </script>
        </div>
</body>

</html>