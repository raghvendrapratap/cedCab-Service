<?php
session_start();
if (!isset($_SESSION['userInfo'])) {
    header('Location: index.php');
} elseif ($_SESSION['userInfo']['is_admin'] == 0) {
    header('Location: index.php');
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
    if (isset($_GET['userid'])) {
        $action = $_GET['action'];
        $userid = $_GET['userid'];

        if ($action == "block") {
            $isblock = 0;
            $sql = $user->updateUserStatus($userid, $isblock, $dbconn->conn);
        } elseif ($action == "unblock") {
            $isblock = 1;
            $sql = $user->updateUserStatus($userid, $isblock, $dbconn->conn);
        } elseif ($action == "delete") {
            $sql = $user->deleteUser($userid, $dbconn->conn);
        }
    }
}

if (isset($_GET['status'])) {

    $status = $_GET['status'];
    if ($_GET['status'] == "blocked") {
        $isblock = 0;
        $result = $user->allUserFilter($isblock, $dbconn->conn);
    } elseif ($_GET['status'] == "unblocked") {
        $isblock = 1;
        $result = $user->allUserFilter($isblock, $dbconn->conn);
    } else {
        $result = $user->allUser($dbconn->conn);
    }
} else {
    header('Location: adminusers.php?status=all');
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
                <a class="<?php if ($fileaction[0] == "adminusers.php?status=all") : ?> active<?php endif; ?>"
                    href="adminusers.php?status=all" id="all">All Users</a>
                <a class="<?php if ($fileaction[0] == "adminusers.php?status=blocked") : ?> active<?php endif; ?>"
                    href="adminusers.php?status=blocked" id="blocked">Pending/Blocked Users</a>
                <a class="<?php if ($fileaction[0] == "adminusers.php?status=unblocked") : ?> active<?php endif; ?>"
                    href="adminusers.php?status=unblocked" id="unblocked">Unblocked Users</a>
                <a class="" href="adminaccount.php" id="accName">Welcome : <?php echo $name; ?> </a>
            </div>

            <div id="ridetable">
                <table id="ride">
                    <thead>
                        <tr>
                            <th>Userame</th>
                            <th>Name</th>
                            <th>Signup Date & Time</th>
                            <th>Mob No.</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <?php
                        if ($result != '') {
                            $totaluser = 0;
                            while ($row = $result->fetch_assoc()) {
                                $totaluser += 1;
                        ?>
                        <tr>
                            <td><?php echo $row['user_name'] ?></td>
                            <td><?php echo $row['name'] ?></td>
                            <td><?php echo $row['dateofsignup'] ?></td>
                            <td><?php echo $row['mobile'] ?></td>
                            <td><?php if ($row['isblock'] == 0) {
                                            echo "Blocked";
                                        } elseif ($row['isblock'] == 1) {
                                            echo "Unblocked";
                                        } ?>
                            </td>
                            <td id="action"><?php if ($row['isblock'] == 0) : ?><a
                                    href="<?php echo $fileaction[0]; ?>&action=unblock&userid=<?php echo $row['user_id']; ?>"
                                    id="aproove">Unblock</a><?php endif; ?><?php if ($row['isblock'] == 1) : ?><a
                                    href="<?php echo $fileaction[0]; ?>&action=block&userid=<?php echo $row['user_id']; ?>"
                                    id="cancel">Block</a><?php endif; ?><a
                                    href="<?php echo $fileaction[0]; ?>&action=delete&userid=<?php echo $row['user_id']; ?>"
                                    id="delete">Delete</a></td>
                        </tr>
                        <?php  } ?>
                        <tr>
                            <td colspan="6">No. of users : <?php echo $totaluser; ?></td>
                        </tr>
                        <?php } ?>
                    <tbody>
                </table>
            </div>

        </div>
        <script>
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
                        "' id='delete'>Delete</a>";
                } else if (value.status == 1) {
                    $button = "<a href='" + fileaction + "&action=aproove&rideid=" + value.ride_id +
                        "' id='aproove'>Approve</a><a href='" + fileaction + "&action=cancel&rideid=" + value
                        .ride_id + "' id='cancel'>Cancel</a><a href='" + fileaction + "&action=delete&rideid=" +
                        value.ride_id + "' id='delete'>Delete</a>";
                    $status = "Pending";
                } else if (value.status == 2) {
                    $status = "Completed";
                    $fare += parseInt(value['total_fare']);
                    $button = "<a href='" + fileaction + "&action=delete&rideid=" + value.ride_id +
                        "' id='delete'>Delete</a>";
                }
                table += "<tr><td> " + value[0].name + "</td><td> " + value.ride_date + "</td><td>" + value
                    .from_distance + "</td><td>" + value.to_distance + "</td><td>" + value.luggage +
                    " Kg</td><td>" + value.total_distance + " km</td><td>" + value.cabType + "</td><td>Rs. " +
                    value.total_fare + "</td><td>" + $status + "</td><td id='action'>" + $button + "</td></tr>";
            });
            table += "<tr><td colspan='7'>Total spent on CedCab :</td><td colspan='3'>Rs. " + $fare + "</td></tr>"
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
        </script>
    </div>
</body>

</html>