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

            <div id="invoice">

                <p class="logopara">Ced<span class="logospan border-radius">Cab</span></p>
                <h2>Your Invoice</h2>

                <div>
                    <?php
                    if (isset($rideInfo) && isset($userInfo)) {
                    ?>

                    <p class="left">Customer ID </p>
                    <p class="right"> <?php echo $userInfo['user_id']; ?></p>
                    <p class="left"> Customer Name </p>
                    <p class="right"> <?php echo $userInfo['name']; ?></p>
                    <p class="left"> Pickup Point </p>
                    <p class="right"> <?php echo $rideInfo['from_distance']; ?></p>
                    <p class="left"> Destination Point </p>
                    <p class="right"> <?php echo $rideInfo['to_distance']; ?></p>
                    <p class="left"> Cab Type </p>
                    <p class="right"> <?php echo $rideInfo['cabType']; ?></p>
                    <p class="left"> Total luggage </p>
                    <p class="right"> <?php echo $rideInfo['luggage']; ?> Kg.</p>
                    <p class="left"> Total Distance Travelled </p>
                    <p class="right"><?php echo $rideInfo['total_distance']; ?> km.</p>
                    <p class="left"> Total Fare </p>
                    <p class="right">Rs. <?php if ($rideInfo['status'] == 2) {
                                                    echo $rideInfo['total_fare'];
                                                } else {
                                                    echo 0;
                                                } ?>
                    </p>
                    <p class="left">
                        <button id="print">Print</button>
                    </p>
                    <?php
                    } ?>
                </div>
            </div>
        </div>
        <script>
        </script>
    </div>
</body>

</html>