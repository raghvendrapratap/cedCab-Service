<?php
include_once("dbconn.php");

class tableLocation
{

    function allLocation($conn)
    {

        $sql = "SELECT * from `tbl_location` WHERE `is_available`=1  ORDER BY `distance`";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        }
    }

    function allLocationsAdmin($conn)
    {

        $sql = "SELECT * from `tbl_location` ORDER BY `distance`";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        }
    }

    function addLocations($locationName, $distance, $conn)
    {

        $sql = "INSERT INTO `tbl_location` (`name`, `distance`,`is_available`) VALUES('$locationName','$distance',1)";
        if ($conn->query($sql) === true) {
            return "Added";
        } else {
            return "Failed";
        }
    }

    function updateLocationStatus($id, $isAvailable, $conn)
    {

        $sql = "UPDATE  `tbl_location` SET `is_available`=$isAvailable WHERE `id`=$id";
        if ($conn->query($sql) === true) {
            return "Updated";
        } else {
            return "Failed";
        }
    }

    function deleteLocation($id, $conn)
    {

        $sql = "DELETE from `tbl_location`  WHERE `id`=$id";
        if ($conn->query($sql) === true) {
            return "Deleted";
        } else {
            return "Failed";
        }
    }

    function countallLocationsAdmin($conn)
    {

        $sql = "SELECT * from `tbl_location`";
        $result = $conn->query($sql);
        return $result->num_rows;
    }

    function countallLocationsFilter($isAvailable, $conn)
    {

        $sql = "SELECT * from `tbl_location` WHERE `is_available`=$isAvailable";
        $result = $conn->query($sql);

        return $result->num_rows;
    }
}