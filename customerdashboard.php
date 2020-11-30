<?php
session_start();
if (!isset($_SESSION['userInfo'])) {
    header('Location: index.php');
} elseif ($_SESSION['userInfo']['is_admin'] == 1) {
    header('Location: index.php');
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

?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
            <a class="<?php if ($file[0] == "customerdashboard.php") : ?> active<?php endif; ?>"
                href="customerdashboard.php">Home</a>
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
                    <a href="yourride.php?status=pending">
                        <div class="col first">
                            <p class="num"><?php echo $pendingRide; ?></p>
                            <p class="text">Pending Rides</p>
                        </div>
                    </a>
                    <a href="yourride.php?status=completed">
                        <div class="col second">
                            <p class="num"><?php echo $completeRide; ?></p>
                            <p class="text">Completed Rides</p>
                        </div>
                    </a>
                    <a href="yourride.php?status=cancelled">
                        <div class="col third">
                            <p class="num"><?php echo $cancelRide; ?></p>
                            <p class="text">Cancelled Rides</p>
                        </div>
                    </a>
                </div>


            </div>
        </div>

    </div>
</body>

</html>