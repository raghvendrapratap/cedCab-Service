<?php

session_start();
if (!isset($_SESSION['userInfo'])) {
    header('Location: index.php');
} elseif ($_SESSION['userInfo']['is_admin'] == 0) {
    header('Location: index.php');
}

$filename = basename($_SERVER['REQUEST_URI']);
$file = explode('?', $filename);

include_once("dbconn.php");
include_once("tableRide.php");
include_once("users.php");
include_once("table_location.php");

$dbconn = new dbconn();
$tableRide = new tableRide();
$user = new user();
$tablelocation = new tablelocation();

if (isset($_GET['action'])) {
    if (isset($_GET['rideid'])) {
        $action = $_GET['action'];
        $rideid = $_GET['rideid'];

        if ($action == "aproove") {
            $statusid = 2;
            $result = $tableRide->updateStatus($rideid, $statusid, $dbconn->conn);
        } elseif ($action == "cancel") {
            $statusid = 0;
            $result = $tableRide->updateStatus($rideid, $statusid, $dbconn->conn);
        } elseif ($action == "delete") {
            $result = $tableRide->deleteRide($rideid, $dbconn->conn);
        }
    }
}

$customerid = isset($_SESSION['userInfo']['customerid']) ? $_SESSION['userInfo']['customerid'] : 0;
$name = isset($_SESSION['userInfo']['name']) ? $_SESSION['userInfo']['name'] : '';

$status = 1;
$pendingRide = $tableRide->countFilterAllRide($status, $dbconn->conn);
$status = 2;
$completeRide = $tableRide->countFilterAllRide($status, $dbconn->conn);
$status = 0;
$cancelRide = $tableRide->countFilterAllRide($status, $dbconn->conn);
$allRide = $tableRide->countAllRide($dbconn->conn);

$isblock = 0;
$pendingUser = $user->countallUserFilter($isblock, $dbconn->conn);
$isblock = 1;
$unBlockedUser = $user->countallUserFilter($isblock, $dbconn->conn);
$allUser = $user->countallUser($dbconn->conn);

$isAvailable = 1;
$enablelocation = $tablelocation->countallLocationsFilter($isAvailable, $dbconn->conn);
$isAvailable = 0;
$disablelocation = $tablelocation->countallLocationsFilter($isAvailable, $dbconn->conn);

$alllocation = $tablelocation->countallLocationsAdmin($dbconn->conn);

$firstPendingRide = $tableRide->firstPendingRide($dbconn->conn);

$thismonth = $tableRide->adminearningThisMonth($dbconn->conn);
$thismonthfare = 0;
if (isset($thismonth)) {
    while ($row = $thismonth->fetch_assoc()) {
        $thismonthfare += $row['total_fare'];
    }
}
$alladminride = $tableRide->allAdminRide($dbconn->conn);
$totalfare = 0;
if (isset($alladminride)) {
    while ($row = $alladminride->fetch_assoc()) {
        if ($row['status'] == 2) {
            $totalfare += $row['total_fare'];
        }
    }
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
                <a class="" href="adminaccount.php" id="accName">Welcome : <?php echo $name; ?> </a>
            </div>

            <div id="tiles">
                <div class="row">
                    <div class="col colAdmin first">
                        <?php if ($firstPendingRide->num_rows > 0) {
                            $row = $firstPendingRide->fetch_assoc();
                            $customerid = $row['customer_user_id'];
                            $userInfo = $user->getUserInfo($customerid, $dbconn->conn); ?>
                        <div class="coldiv ">
                            <p class="textRide"><?php echo $pendingRide; ?> <br> New <br>Ride </br> Requests</p>
                        </div>
                        <div class="coldiv ">
                            <p class="text1"><?php echo $row['from_distance'];; ?> To <?php echo $row['to_distance']; ?>
                            </p>
                            <p class="text2">Date : <?php echo $row['ride_date']; ?></p>
                            <p class="text2">Customer Name : <?php echo $userInfo['name'] ?></p>
                            <p class="text">New Ride Request</p>
                        </div>
                        <div class="coldiv ">
                            <p class="pbtn">
                                <a id="approvebtn" class="abtn"
                                    href="admindashboard.php?action=aproove&rideid=<?php echo $row['ride_id']; ?>">Approve</a>
                            </p>
                            <p class="pbtn"><a id="cancelbtn" class="abtn"
                                    href="admindashboard.php?action=cancel&rideid=<?php echo $row['ride_id']; ?>">Cancel</a>
                            </p>
                            <p class="pbtn">
                                <a id="deletebtn" class="abtn"
                                    href="admindashboard.php?action=delete&rideid=<?php echo $row['ride_id']; ?>">Delete</a>
                            </p>
                        </div>
                        <?php
                        } else { ?>

                        <p class="num">0</p>
                        <p class="text">New Ride Request</p>

                        <?php
                        } ?>
                    </div>
                </div>
                <div class="row">
                    <a>
                        <div class="col first">
                            <p class="num">Rs. <?php echo $thismonthfare; ?></p>
                            <p class="text">Total earning this month.</p>
                        </div>
                    </a>
                    <a>
                        <div class="col second">
                            <p class="num">Rs. <?php echo $totalfare; ?></p>
                            <p class="text">Total earning till now.</p>
                        </div>
                    </a>
                    <a href="adminrides.php?status=all">
                        <div class="col third">
                            <p class="num"><?php echo $allRide; ?></p>
                            <p class="text">All Rides</p>
                        </div>
                    </a>
                </div>
                <div class="row">
                    <a href="adminrides.php?status=pending">
                        <div class="col first">
                            <p class="num"><?php echo $pendingRide; ?></p>
                            <p class="text">Pending Rides</p>
                        </div>
                    </a>
                    <a href="adminrides.php?status=completed">
                        <div class="col second">
                            <p class="num"><?php echo $completeRide; ?></p>
                            <p class="text">Completed Rides</p>
                        </div>
                    </a>
                    <a href="adminrides.php?status=cancelled">
                        <div class="col third">
                            <p class="num"><?php echo $cancelRide; ?></p>
                            <p class="text">Cancelled Rides</p>
                        </div>
                    </a>
                </div>
                <div class="row">
                    <a href="adminusers.php?status=blocked">
                        <div class="col first">
                            <p class="num"><?php echo $pendingUser; ?></p>
                            <p class="text">Pending/Blocked Users</p>
                        </div>
                    </a>
                    <a href="adminusers.php?status=unblocked">
                        <div class="col second">
                            <p class="num"><?php echo $unBlockedUser; ?></p>
                            <p class="text">Approved Users</p>
                        </div>
                    </a>
                    <a href="adminusers.php?status=all">
                        <div class="col third">
                            <p class="num"><?php echo $allUser; ?></p>
                            <p class="text">All Users</p>
                        </div>
                    </a>
                </div>
                <div class="row">
                    <a href="adminlocations.php">
                        <div class="col first">
                            <p class="num"><?php echo $disablelocation; ?></p>
                            <p class="text">Blocked Location</p>
                        </div>
                    </a>
                    <a href="adminlocations.php">
                        <div class="col second">
                            <p class="num"><?php echo $enablelocation; ?></p>
                            <p class="text">Approved Location</p>
                        </div>
                    </a>
                    <a href="adminlocations.php">
                        <div class="col third">
                            <p class="num"><?php echo $alllocation; ?></p>
                            <p class="text">All location</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>
        <script>
        </script>
    </div>
</body>

</html>