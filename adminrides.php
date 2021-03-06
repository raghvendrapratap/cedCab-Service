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
        echo "<script type='text/javascript'>alert('Your Session has timed out. Please Login Again.'); window.location='login.php';</script>";
    } else {
        $_SESSION['activeTime'] = time();
    }
} else {
    $_SESSION['activeTime'] = time();
}

$filename = basename($_SERVER['REQUEST_URI']);
$file = explode('?', $filename);
$fileaction = explode('&action', $filename);

include_once("dbconn.php");
include_once("tableRide.php");
include_once("users.php");
$dbconn = new dbconn();
$tableRide = new tableRide();
$user = new user();
$customerid = isset($_SESSION['userInfo']['customerid']) ? $_SESSION['userInfo']['customerid'] : 0;
$name = isset($_SESSION['userInfo']['name']) ? $_SESSION['userInfo']['name'] : '';

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

if (isset($_GET['status'])) {

    $status = $_GET['status'];
    if ($_GET['status'] == "pending") {
        $statusid = 1;
        $result = $tableRide->allAdminFilterRide($statusid, $dbconn->conn);
    } elseif ($_GET['status'] == "cancelled") {
        $statusid = 0;
        $result = $tableRide->allAdminFilterRide($statusid, $dbconn->conn);
    } elseif ($_GET['status'] == "completed") {
        $statusid = 2;
        $result = $tableRide->allAdminFilterRide($statusid, $dbconn->conn);
    } else {
        $status = 'all';
        $result = $tableRide->allAdminRide($dbconn->conn);
    }
} else {
    header('Location: adminrides.php?status=all');
}
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
            <a class="<?php if ($file[0] == "admindashboard.php") : ?> active<?php endif; ?>" href="admindashboard.php">Home</a>
            <a class="<?php if ($file[0] == "adminrides.php") : ?> active<?php endif; ?>" href="adminrides.php?status=all">Rides</a>
            <a class="<?php if ($file[0] == "adminusers.php") : ?> active<?php endif; ?>" href="adminusers.php">Users</a>
            <a class="<?php if ($file[0] == "adminlocations.php") : ?> active<?php endif; ?>" href="adminlocations.php">Locations</a>
            <a class="<?php if ($file[0] == "adminaccount.php") : ?> active<?php endif; ?>" href="adminaccount.php">Your
                Account</a>
            <a href="logout.php">Logout</a>
        </div>

        <div class="content ">
            <div class="topnav">
                <a class="<?php if ($fileaction[0] == "adminrides.php?status=all") : ?> active<?php endif; ?>" href="adminrides.php?status=all" id="all">All Ride</a>
                <a class="<?php if ($fileaction[0] == "adminrides.php?status=pending") : ?> active<?php endif; ?>" href="adminrides.php?status=pending" id="pending">Pending Ride</a>
                <a class="<?php if ($fileaction[0] == "adminrides.php?status=cancelled") : ?> active<?php endif; ?>" href="adminrides.php?status=cancelled" id="cancelled">Cancelled Ride</a>
                <a class="<?php if ($fileaction[0] == "adminrides.php?status=completed") : ?> active<?php endif; ?>" href="adminrides.php?status=completed" id="completed">Completed Ride</a>
                <a class="" href="adminaccount.php" id="accName">Welcome : <?php echo $name; ?> </a>
            </div>

            <div id="sort">
                <label for="sortby">Sort By</label>
                <select class="" id="sortby">
                    <option value="all" selected>All Ride</option>
                    <option value="highfare">Highest Fare</option>
                    <option value="lowfare">Lowest Fare</option>
                    <option value="highdistance">Highest Distance</option>
                    <option value="lowdistance">Lowest Distance</option>
                </select>
            </div>

            <div id="filter">
                <label for="filterbydate">Filter By Date</label>
                <select class="" id="filterbydate">
                    <option value="" selected>All Ride</option>
                    <option value="today">Last 24 hrs</option>
                    <option value="last7days">Last 7 days</option>
                    <option value="last30days">Last 30 days</option>
                </select>
            </div>

            <div id="ridetable">
                <table id="ride">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Date & Time</th>
                            <th>Pickup</th>
                            <th>Drop</th>
                            <th>Luggage</th>
                            <th>Total Distance</th>
                            <th>Cab Type</th>
                            <th>Fare</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <?php
                        if ($result != '') {
                            $totalfare = 0;
                            while ($row = $result->fetch_assoc()) {
                                $user_id = $row['customer_user_id'];
                                $userInfo = $user->getUserInfo($user_id, $dbconn->conn);
                        ?>
                                <tr>
                                    <td><?php echo $userInfo['name'] ?></td>
                                    <td><?php echo $row['ride_date'] ?></td>
                                    <td><?php echo $row['from_distance'] ?></td>
                                    <td><?php echo $row['to_distance'] ?></td>
                                    <td><?php echo $row['luggage'] ?> Kg</td>
                                    <td><?php echo $row['total_distance'] ?> km</td>
                                    <td><?php echo $row['cabType'] ?></td>
                                    <td>Rs. <?php echo $row['total_fare'] ?></td>
                                    <td>
                                        <?php if ($row['status'] == 0) {
                                            echo "Cancelled";
                                        } elseif ($row['status'] == 1) {
                                            echo "Pending";
                                        } elseif ($row['status'] == 2) {
                                            $totalfare += $row['total_fare'];
                                            echo "Completed";
                                        }
                                        ?>
                                    </td>
                                    <td id="action">
                                        <?php if ($row['status'] == 1) : ?>
                                            <a href="<?php echo $fileaction[0]; ?>&action=aproove&rideid=<?php echo $row['ride_id']; ?>" id="aproove">Approve</a>

                                            <a href="<?php echo $fileaction[0]; ?>&action=cancel&rideid=<?php echo $row['ride_id']; ?>" id="cancel">Cancel</a>
                                        <?php endif; ?>
                                        <a href="<?php echo $fileaction[0]; ?>&action=delete&rideid=<?php echo $row['ride_id']; ?>" id="delete">Delete</a>
                                        <a href="invoice.php?action=view&rideid=<?php echo $row['ride_id']; ?>&userid=<?php echo $userInfo['user_id'] ?>" id="view">Invoice</a>
                                    </td>
                                </tr>
                            <?php  } ?>
                            <tr>
                                <td colspan="7">Your total earning :</td>
                                <td colspan="3">Rs. <?php echo $totalfare; ?></td>
                            </tr>
                        <?php } ?>
                    <tbody>
                </table>
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
            $(".onlytext").bind("keypress", function(e) {
                var keyCode = e.which ? e.which : e.keyCode

                if (!(keyCode >= 48 && keyCode <= 57)) {
                    $(".error").css("display", "inline");
                    return false;
                } else {
                    $(".error").css("display", "none");
                }
            })

            function showTable(msg) {
                var fileaction = '<?php echo $fileaction[0]; ?>';
                console.log(status);
                console.log(msg);
                var table = "";
                $fare = 0;
                $.each(msg, function(i, value) {
                    $button = '';
                    if (value.status == 0) {
                        $status = "Cancelled";
                        $button = "<a href='" + fileaction + "&action=delete&rideid=" + value.ride_id +
                            "' id='delete'>Delete</a><a href='invoice.php?action=view&rideid=" + value.ride_id +
                            "&userid=" + value[0].user_id + "' id='view'>Invoice</a>";
                    } else if (value.status == 1) {
                        $button = "<a href='" + fileaction + "&action=aproove&rideid=" + value.ride_id +
                            "' id='aproove'>Approve</a><a href='" + fileaction + "&action=cancel&rideid=" + value
                            .ride_id + "' id='cancel'>Cancel</a><a href='" + fileaction + "&action=delete&rideid=" +
                            value.ride_id + "' id='delete'>Delete</a><a href='invoice.php?action=view&rideid=" +
                            value.ride_id +
                            "&userid=" + value[0].user_id + "' id='view'>Invoice</a>";
                        $status = "Pending";
                    } else if (value.status == 2) {
                        $status = "Completed";
                        $fare += parseInt(value['total_fare']);
                        $button = "<a href='" + fileaction + "&action=delete&rideid=" + value.ride_id +
                            "' id='delete'>Delete</a><a href='invoice.php?action=view&rideid=" + value.ride_id +
                            "&userid=" + value[0].user_id + "' id='view'>Invoice</a>";
                    }
                    table += "<tr><td> " + value[0].name + "</td><td> " + value.ride_date + "</td><td>" + value
                        .from_distance + "</td><td>" + value.to_distance + "</td><td>" + value.luggage +
                        " Kg</td><td>" + value.total_distance + " km</td><td>" + value.cabType + "</td><td>Rs. " +
                        value.total_fare + "</td><td>" + $status + "</td><td id='action'>" + $button + "</td></tr>";
                });
                table += "<tr><td colspan='7'>Your total earning :</td><td colspan='3'>Rs. " + $fare + "</td></tr>"
                $("#tbody").html(table);
            }

            $(function() {
                var status = '<?php echo $status; ?>';
                $("#sortby").change(function() {
                    var sortby = $("#sortby").val();

                    $.ajax({
                        url: 'ajax.php',
                        type: 'POST',
                        data: {
                            sortby: sortby,
                            status: status,
                            action: 'sortAdminRide',
                        },
                        dataType: "json",
                        success: function(result) {
                            console.log(result);
                            showTable(result);
                        },
                        error: function() {
                            console.log("Error");
                        }
                    })
                });
            })

            $(function() {
                var status = '<?php echo $status; ?>';
                $("#filterbydate").change(function() {
                    var filterbydate = $("#filterbydate").val();
                    $.ajax({
                        url: 'ajax.php',
                        type: 'POST',
                        data: {
                            filterbydate: filterbydate,
                            status: status,
                            action: 'filterbydateAdminRide',
                        },
                        dataType: "json",
                        success: function(result) {
                            console.log(result);
                            $("#tbody").html("");
                            showTable(result);
                        },
                        error: function() {
                            console.log("Error");
                        }
                    })
                });
            })
        </script>
    </div>
</body>

</html>