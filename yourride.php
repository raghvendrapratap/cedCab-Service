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
        echo "<script type='text/javascript'>alert('Your Session has timed out. Please Login Again.'); window.location='index.php';</script>";
    } else {
        $_SESSION['activeTime'] = time();
    }
} else {
    $_SESSION['activeTime'] = time();
}

$filename = basename($_SERVER['REQUEST_URI']);
$file = explode('?', $filename);
$fileaction = explode('&', $filename);

include_once("dbconn.php");
include_once("tableRide.php");
$dbconn = new dbconn();
$tableRide = new tableRide();
$customerid = isset($_SESSION['userInfo']['customerid']) ? $_SESSION['userInfo']['customerid'] : 0;

if (isset($_GET['action'])) {
    if (isset($_GET['rideid'])) {
        $action = $_GET['action'];
        $rideid = $_GET['rideid'];

        if ($action == "cancel") {
            $statusid = 0;
            $result = $tableRide->updateStatus($rideid, $statusid, $dbconn->conn);
        }
    }
}

if (isset($_GET['status'])) {

    $status = $_GET['status'];
    if ($_GET['status'] == "pending") {
        $result = $tableRide->pendingRide($customerid, $dbconn->conn);
    } elseif ($_GET['status'] == "cancelled") {
        $result = $tableRide->cancelledRide($customerid, $dbconn->conn);
    } elseif ($_GET['status'] == "completed") {
        $result = $tableRide->completedRide($customerid, $dbconn->conn);
    } else {
        $status = 'all';
        $result = $tableRide->allRide($customerid, $dbconn->conn);
    }
} else {
    header('Location: yourride.php?status=all');
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
    <div id="customer">
        <div class="sidebar">
            <p class="logopara">Ced<span class="logospan border-radius">Cab</span>
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
                <a class="<?php if ($fileaction[0] == "yourride.php?status=all") : ?> active<?php endif; ?>"
                    href="yourride.php" id="all">All Ride</a>
                <a class="<?php if ($fileaction[0] == "yourride.php?status=pending") : ?> active<?php endif; ?>"
                    href="yourride.php?status=pending" id="pending">Pending Ride</a>
                <a class="<?php if ($fileaction[0] == "yourride.php?status=cancelled") : ?> active<?php endif; ?>"
                    href="yourride.php?status=cancelled" id="cancelled">Cancelled Ride</a>
                <a class="<?php if ($fileaction[0] == "yourride.php?status=completed") : ?> active<?php endif; ?>"
                    href="yourride.php?status=completed" id="completed">Completed Ride</a>
                <a class="" href="yourprofile.php" id="accName">Welcome : <?php echo $_SESSION['userInfo']['name']; ?>
                </a>
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
                            while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['ride_date'] ?></td>
                            <td><?php echo $row['from_distance'] ?></td>
                            <td><?php echo $row['to_distance'] ?></td>
                            <td><?php echo $row['luggage'] ?> Kg</td>
                            <td><?php echo $row['total_distance'] ?> km</td>
                            <td><?php echo $row['cabType'] ?></td>
                            <td>Rs. <?php echo $row['total_fare'] ?></td>
                            <td><?php if ($row['status'] == 0) {
                                            echo "Cancelled";
                                        } elseif ($row['status'] == 1) {
                                            echo "Pending";
                                        } elseif ($row['status'] == 2) {
                                            $totalfare += $row['total_fare'];
                                            echo "Completed";
                                        } ?>
                            </td>
                            <td id="action"><?php if ($row['status'] == 1) : ?>
                                <a href="<?php echo $fileaction[0]; ?>&action=cancel&rideid=<?php echo $row['ride_id']; ?>"
                                    id="cancel">Cancel</a>
                                <?php endif; ?>
                                <a href="invoice.php?action=view&rideid=<?php echo $row['ride_id']; ?>&userid=<?php echo $customerid; ?>"
                                    id="view">Invoice</a>
                            </td>
                        </tr>
                        <?php  } ?>
                        <tr>
                            <td colspan="6">Total spent on CedCab :</td>
                            <td colspan="3">Rs. <?php echo $totalfare; ?></td>
                        </tr>
                        <?php } ?>
                    <tbody>
                </table>
            </div>

        </div>
        <script>
        function showTable(msg) {
            var customerid = '<?php echo $customerid; ?>';
            var fileaction = '<?php echo $fileaction[0]; ?>';
            console.log(msg);
            var table = "";
            $fare = 0;
            $.each(msg, function(i, value) {
                $button = '';
                if (value.status == 0) {
                    $status = "Cancelled";
                } else if (value.status == 1) {
                    $button = "<a href='" + fileaction + "&action=cancel&rideid=" + value
                        .ride_id + "' id='cancel'>Cancel</a>";
                    $status = "Pending";
                } else if (value.status == 2) {
                    $status = "Completed";
                    $fare += parseInt(value['total_fare']);
                    console.log(typeof value['total_fare']);
                }
                table += "<tr><td> " + value.ride_date + "</td><td>" + value.from_distance + "</td><td>" + value
                    .to_distance + "</td><td>" + value.luggage + " Kg</td><td>" + value.total_distance +
                    " km</td><td>" + value.cabType + "</td><td>Rs. " + value.total_fare + "</td><td>" +
                    $status + "</td><td id='action'>" + $button + "<a href='invoice.php?action=view&rideid=" +
                    value.ride_id +
                    "&userid=" + customerid + "' id='view'>Invoice</a></td></tr>";
            });
            table += "<tr><td colspan='6'>Total spent on CedCab :</td><td colspan='3'>Rs. " + $fare + "</td></tr>"
            $("#tbody").html(table);
        }
        $(function() {
            var status = '<?php echo $status; ?>';
            var customerid = '<?php echo $customerid; ?>';
            $("#sortby").change(function() {
                var sortby = $("#sortby").val();

                $.ajax({
                    url: 'ajax.php',
                    type: 'POST',
                    data: {
                        sortby: sortby,
                        status: status,
                        customerid: customerid,
                        action: 'sortRide',
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
            var customerid = '<?php echo $customerid; ?>';
            $("#filterbydate").change(function() {
                var filterbydate = $("#filterbydate").val();
                $.ajax({
                    url: 'ajax.php',
                    type: 'POST',
                    data: {
                        customerid: customerid,
                        filterbydate: filterbydate,
                        status: status,
                        action: 'filterbydateRide',
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