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
        $sql1 = "SELECT * from `tbl_location` WHERE name='$locationName'";
        $result1 = $conn->query($sql1);
        if ($result1->num_rows > 0) {
            echo "<script type='text/javascript'>alert('Location Name already exists.');</script>";
        } else {
            $sql = "INSERT INTO `tbl_location` (`name`, `distance`,`is_available`) VALUES('$locationName','$distance',1)";
            if ($conn->query($sql) === true) {
                echo "<script type='text/javascript'>alert('" . $locationName . " location added Succssfully');</script>";
            } else {
                echo "<script type='text/javascript'>alert('Failed, Enter valid details');</script>";
            }
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

    function checklname($lname, $conn)
    {
        $sql = "SELECT * from `tbl_location` WHERE name='$lname'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return $result;
        }
    }

    function checklupdatename($locationid, $updatename, $conn)
    {
        $sql = "SELECT * from `tbl_location` WHERE name='$updatename'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['id'] != $locationid) {
                return "Invalid";
            }
        }
    }

    function updateLocations($locationid, $locationname, $locationdistance, $conn)
    {
        $sql = "SELECT * from `tbl_location` WHERE `name`='$locationname'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['id'] == $locationid) {
                $sql = "UPDATE  `tbl_location` SET `name`='$locationname',`distance`='$locationdistance' WHERE `id`=$locationid";
                if ($conn->query($sql) === true) {
                    echo "<script type='text/javascript'>alert('Location updated Succssfully');</script>";
                } else {
                    echo "<script type='text/javascript'>alert('Updation Failed');</script>";
                }
            } else {
                echo "<script type='text/javascript'>alert('Location Name already exists');</script>";
            }
        } else {
            $sql = "UPDATE  `tbl_location` SET `name`='$locationname',`distance`='$locationdistance' WHERE `id`=$locationid";
            if ($conn->query($sql) === true) {
                echo "<script type='text/javascript'>alert('Location updated Succssfully');</script>";
            } else {
                echo "<script type='text/javascript'>alert('Updation Failed');</script>";
            }
        }
    }
}
