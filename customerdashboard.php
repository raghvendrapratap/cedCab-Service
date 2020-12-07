<?php
session_start();
if (!isset($_SESSION['userInfo'])) {
    header('Location: index.php');
} elseif ($_SESSION['userInfo']['is_admin'] == 1) {
    header('Location: index.php');
}
if (isset($_SESSION['activeTime'])) {
    if (time() - $_SESSION['activeTime'] > 300) {
        session_destroy();
        echo "<script type='text/javascript'>alert('Your Session has timed out. Please Login Again.'); window.location='login.php';</script>";
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

$customerid = isset($_SESSION['userInfo']['customerid']) ? $_SESSION['userInfo']['customerid'] : 0;
$name = isset($_SESSION['userInfo']['name']) ? $_SESSION['userInfo']['name'] : '';


$dbconn = new dbconn();
$tableRide = new tableRide();
$status = 1;
$pendingRide = $tableRide->countFilterUserAllRide($customerid, $status, $dbconn->conn);
$status = 2;
$completeRide = $tableRide->countFilterUserAllRide($customerid, $status, $dbconn->conn);
$status = 0;
$cancelRide = $tableRide->countFilterUserAllRide($customerid, $status, $dbconn->conn);
$result = $tableRide->allRide($customerid, $dbconn->conn);
$totalfare = 0;
if (isset($result)) {
    while ($row = $result->fetch_assoc()) {
        if ($row['status'] == 2) {
            $totalfare += $row['total_fare'];
        }
    }
}
$thismonth = $tableRide->allRideThisMonth($customerid, $dbconn->conn);
$thismonthfare = 0;
if (isset($thismonth)) {
    while ($row = $thismonth->fetch_assoc()) {
        if ($row['status'] == 2) {
            $thismonthfare += $row['total_fare'];
        }
    }
}

$lastRide = $tableRide->userlastRide($customerid, $dbconn->conn);
$lastRidestatus = '';
$lastRidepickup = '';
$lastRidedrop = '';
$lastRideDate = '';
if (isset($lastRide)) {
    $row = $lastRide->fetch_assoc();
    $lastRidepickup = $row['from_distance'];
    $lastRidedrop = $row['to_distance'];
    $lastRideDate = $row['ride_date'];;
    if ($row['status'] == 0) {
        $lastRidestatus = "Cancelled";
    } else if ($row['status'] == 1) {
        $lastRidestatus = "Pending";
    } else if ($row['status'] == 2) {
        $lastRidestatus = "Campleted";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/4b2ee26aaa.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="cab.js"></script>
    <link rel="stylesheet" href="style.css">
    <title>Ced Cab</title>
</head>

<body>
    <div id="customer">
        <div class="sidebar">

            <p class="logopara">Ced<span class="logospan border-radius">Cab</span>
            </p>
            </p>
            <a class="<?php if ($file[0] == "customerdashboard.php") : ?> active<?php endif; ?>" href="customerdashboard.php">Home</a>
            <a class="<?php if ($file[0] == "yourride.php") : ?> active<?php endif; ?>" href="yourride.php">Your
                Ride</a>
            <a class="<?php if ($file[0] == "yourprofile.php") : ?> active<?php endif; ?>" href="yourprofile.php">Your
                Profile</a>
            <a href="index.php">Book Cab</a>
            <a href="logout.php">Logout</a>
        </div>

        <div class="content ">
            <div class="topnav">
                <a class="" href="yourprofile.php" id="accName">Welcome : <?php echo $_SESSION['userInfo']['name']; ?>
                </a>
            </div>
            <div id="tiles">

                <div class="row">

                    <div class="col firstc">
                        <p class="num">Rs.<?php echo $thismonthfare; ?></p>
                        <p class="text">Your total spent this month</p>
                    </div>


                    <div class="col secondc">
                        <p class="num">Rs. <?php echo $totalfare; ?></p>
                        <p class="text">Your total spent till now</p>
                    </div>

                    <a href="yourride.php?status=completed">
                        <div class="col thirdc">
                            <p class="num"><?php echo $completeRide; ?></p>
                            <p class="text">Completed Rides</p>
                        </div>
                    </a>

                </div>

                <div class="row">
                    <a href="">
                        <div class="col col1 first">
                            <p class="text1"><?php echo $lastRidepickup; ?> To <?php echo $lastRidedrop; ?></p>
                            <p class="text2">Date : <?php echo $lastRideDate; ?></p>
                            <p class="text2">Status : <?php echo $lastRidestatus; ?></p>
                            <p class="text">Your Last Ride</p>
                        </div>
                    </a>
                    <a href="yourride.php?status=pending">
                        <div class="col col2 first">
                            <p class="num"><?php echo $pendingRide; ?></p>
                            <p class="text">Pending Rides</p>
                        </div>
                    </a>
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

    </div>
</body>

</html>