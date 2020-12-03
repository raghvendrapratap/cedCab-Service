<?php
include_once("dbconn.php");

class tableRide
{

    function bookRide($pickup, $drop, $cabType, $luggage, $fare, $dateofride, $distance, $status, $customerid, $conn)
    {

        $sql = "INSERT INTO `tbl_ride` (`ride_date`, `from_distance`,`to_distance`, `total_distance`,`luggage`,`total_fare`, `status`,`customer_user_id`,`cabType`) VALUES('$dateofride','$pickup','$drop',$distance,$luggage,$fare,$status,$customerid,'$cabType')";

        if ($conn->query($sql) === true) {
            echo "<script type='text/javascript'>alert('Your booking request of " . $cabType . " cab from " . $pickup . " to " . $drop . " has been sent.'); window.location='customerdashboard.php';</script>";
        } else {
            echo $conn->error;
        }
    }

    function allRide($customerid, $conn)
    {

        $sql = "SELECT * from `tbl_ride` WHERE `customer_user_id`=$customerid";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        }
    }

    function pendingRide($customerid, $conn)
    {

        $sql = "SELECT * from `tbl_ride` WHERE `customer_user_id`=$customerid AND `status`=1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        }
    }

    function sortRide($status, $sortBy, $sort, $customerid, $conn)
    {

        $sql = "SELECT * from `tbl_ride` WHERE `customer_user_id`=$customerid AND `status`=$status ORDER BY `$sortBy` $sort";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        }
    }
    function sortAdminRide($status, $sortBy, $sort, $conn)
    {

        $sql = "SELECT * from `tbl_ride` WHERE `status`=$status ORDER BY `$sortBy` $sort";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        }
    }

    function sortAllRide($sortBy, $sort, $customerid, $conn)
    {

        $sql = "SELECT * from `tbl_ride` WHERE `customer_user_id`=$customerid ORDER BY `$sortBy` $sort";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        }
    }
    function sortAllAdminRide($sortBy, $sort, $conn)
    {

        $sql = "SELECT * from `tbl_ride` ORDER BY `$sortBy` $sort";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        }
    }

    function cancelledRide($customerid, $conn)
    {

        $sql = "SELECT * from `tbl_ride` WHERE `customer_user_id`=$customerid AND `status`=0";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        }
    }
    function completedRide($customerid, $conn)
    {

        $sql = "SELECT * from `tbl_ride` WHERE `customer_user_id`=$customerid AND `status`=2";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        }
    }

    function allAdminFilterRide($statusid, $conn)
    {

        $sql = "SELECT * from `tbl_ride` WHERE `status`=$statusid ";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        }
    }
    function allAdminRide($conn)
    {

        $sql = "SELECT * from `tbl_ride`";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        }
    }
    function updateStatus($rideid, $statusid, $conn)
    {

        $sql = "UPDATE tbl_ride SET `status`=$statusid WHERE `ride_id`=$rideid";
        if ($conn->query($sql) === true) {
            return "Successfully Updated";
        } else {
            return "Updation Failed";
        }
    }
    function deleteRide($rideid, $conn)
    {

        $sql = "DELETE from tbl_ride WHERE `ride_id`=$rideid";
        if ($conn->query($sql) === true) {
            return "Successfully Deleted";
        } else {
            return "Deletion Failed";
        }
    }

    function countFilterAllRide($status, $conn)
    {

        $sql = " SELECT * from `tbl_ride` WHERE `status`=$status ";
        $result = $conn->query($sql);
        return $row = $result->num_rows;
    }
    function countAllRide($conn)
    {
        $sql = " SELECT * from `tbl_ride`";
        $result = $conn->query($sql);
        $row = $result->num_rows;
        return $row;
    }

    function filterbydateRide($status, $interval, $conn)
    {
        $sql = "SELECT * from `tbl_ride` WHERE ride_date > DATE_SUB(NOW(), INTERVAL $interval DAY) AND status=$status";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        }
    }
    function filterbydateRideAll($interval, $conn)
    {
        $sql = "SELECT * from `tbl_ride` WHERE ride_date > DATE_SUB(NOW(), INTERVAL $interval DAY)";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        }
    }
    function filterbydateuserRide($customerid, $status, $interval, $conn)
    {
        $sql = "SELECT * from `tbl_ride` WHERE ride_date > DATE_SUB(NOW(), INTERVAL $interval DAY) AND status=$status AND `customer_user_id`=$customerid";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        }
    }
    function filterbydateUserRideAll($customerid, $interval, $conn)
    {
        $sql = "SELECT * from `tbl_ride` WHERE ride_date > DATE_SUB(NOW(), INTERVAL $interval DAY) AND `customer_user_id`=$customerid";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        }
    }

    function countFilterUserAllRide($customerid, $status, $conn)
    {

        $sql = " SELECT * from `tbl_ride` WHERE `status`=$status AND `customer_user_id`=$customerid";
        $result = $conn->query($sql);
        return $row = $result->num_rows;
    }

    function rideInfo($rideid, $conn)
    {
        $sql = "SELECT * from `tbl_ride` WHERE ride_id= $rideid";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        }
    }
    function allRideThisMonth($customerid, $conn)
    {
        $sql = "SELECT * FROM tbl_ride WHERE MONTH(ride_date) = MONTH(CURRENT_DATE()) AND YEAR(ride_date) = YEAR(CURRENT_DATE()) AND `customer_user_id`=$customerid";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        }
    }
    function userlastRide($customerid, $conn)
    {
        $sql = "SELECT * FROM `tbl_ride` WHERE customer_user_id=$customerid and ride_date=( SELECT max(cast(ride_date as DateTime)) FROM `tbl_ride` WHERE customer_user_id=$customerid )";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        }
    }

    function firstPendingRide($conn)
    {
        $sql = "SELECT * FROM `tbl_ride` WHERE `status`=1 and ride_date=( SELECT MIN(cast(ride_date as DateTime)) FROM `tbl_ride` WHERE `status`=1 )";
        $result = $conn->query($sql);

        return $result;
    }

    function adminearningThisMonth($conn)
    {
        $sql = "SELECT * FROM tbl_ride WHERE MONTH(ride_date) = MONTH(CURRENT_DATE()) AND YEAR(ride_date) = YEAR(CURRENT_DATE()) AND `status`=2";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        }
    }
}