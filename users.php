<?php
include_once("dbconn.php");
include_once("tableRide.php");
class user
{

    function login($username, $password, $conn)
    {
        $sql = "SELECT * from `tbl_user` WHERE `user_name`='$username' AND `password`='$password' AND `isblock`=1";

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['userInfo'] = array('username' => $row['user_name'], 'customerid' => $row['user_id'], 'name' => $row['name'], 'is_admin' => $row['is_admin'], 'mob' => $row['mobile']);

            if ($row['is_admin'] == 1) {
                if (isset($_SESSION['cabInfo'])) {
                    echo "<script type='text/javascript'>alert('You are Admin. You cant book Cab.'); window.location='admindashboard.php';</script>";
                } else {
                    header('Location: admindashboard.php');
                }
            } else if ($row['is_admin'] == 0) {

                if (isset($_SESSION['cabInfo'])) {
                    $pickup = $_SESSION['cabInfo']['pickup'];
                    $drop = $_SESSION['cabInfo']['drop'];
                    $cabType = $_SESSION['cabInfo']['cabType'];
                    $luggage = $_SESSION['cabInfo']['luggage'];
                    $fare = $_SESSION['cabInfo']['fare'];
                    $distance = $_SESSION['cabInfo']['distance'];
                    $dateofride = $_SESSION['cabInfo']['dateofride'];
                    $customerid = $_SESSION['userInfo']['customerid'];
                    $status = 1;
                    $tableRide = new tableRide();
                    $dbconn = new dbconn();
                    $sql = $tableRide->bookRide($pickup, $drop, $cabType, $luggage, $fare, $dateofride, $distance, $status, $customerid, $dbconn->conn);
                    $errors = $sql;
                } else {
                    header('Location: customerdashboard.php');
                }
            }
        } else {
            return "*Invalid Username or Password";
        }
    }

    function signup($username, $name, $dateofsignup, $mob, $isblock, $password, $repassword, $is_admin, $conn)
    {
        $errors = array();

        if ($password != $repassword) {
            $errors[] = array('input' => 'password', 'msg' => 'Password does not match.');
        }

        if (sizeof($errors) == 0) {
            $sql = "SELECT * from `tbl_user` WHERE user_name='$username'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $errors[] = array('input' => 'result', 'msg' => 'Username already exists');
            }
        }
        if (sizeof($errors) == 0) {
            $password = md5($password);
            $sql = "INSERT INTO tbl_user (user_name, name,dateofsignup, mobile,isblock, password,is_admin) VALUES('$username','$name', '$dateofsignup' ,'$mob',$isblock,'$password',$is_admin)";
            if ($conn->query($sql) === true) {
                header('Location:index.php');
            } else {
                $errors[] = array('input' => 'form', 'msg' => $conn->error);
            }
        }
        return  $errors;
    }

    function updateUser($customerid, $username, $name, $mobile, $conn)
    {

        $sql = "UPDATE tbl_user SET `name`='$name', `mobile`='$mobile'  WHERE `user_name`='$username'";
        if ($conn->query($sql) === true) {
            $_SESSION['userInfo']['name'] = $name;
            return "Successfully Updated";
        } else {
            return "Updation Failed";
        }
    }

    function getUserInfo($customerid, $conn)
    {

        $sql = "SELECT * from tbl_user WHERE `user_id`=$customerid";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        }
    }

    function changePass($customerid, $oldpass, $newpass, $conn)
    {
        $oldpass = md5($oldpass);
        $newpass = md5($newpass);
        $sql = "SELECT * from tbl_user WHERE `user_id`=$customerid";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($oldpass == $row['password']) {
                $sql = "UPDATE tbl_user SET `password`='$newpass' WHERE `user_id`=$customerid";
                if ($conn->query($sql) === true) {
                    session_destroy();
                    echo "<script type='text/javascript'>alert('Your Password Changed, Please Login again.'); window.location='login.php';</script>";
                } else {
                    return "Updation Failed";
                }
            } else {
                return "Enter Correct Password";
            }
        }
    }

    function allUserFilter($isblock, $conn)
    {

        $sql = "SELECT * from tbl_user WHERE `isblock`=$isblock AND `is_admin`=0";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return $result;
        }
    }

    function allUser($conn)
    {

        $sql = "SELECT * from tbl_user WHERE `is_admin`=0";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return $result;
        }
    }

    function updateUserStatus($userid, $isblock, $conn)
    {

        $sql = "UPDATE tbl_user SET `isblock`=$isblock WHERE `user_id`=$userid";
        if ($conn->query($sql) === true) {
            return "Updated";
        }
    }
    function deleteUser($userid, $conn)
    {

        $sql = "DELETE from  tbl_user WHERE `user_id`=$userid";
        if ($conn->query($sql) === true) {
            return "Updated";
        }
    }

    function countallUserFilter($isblock, $conn)
    {

        $sql = "SELECT * from tbl_user WHERE `isblock`=$isblock AND `is_admin`=0";
        $result = $conn->query($sql);
        return $result->num_rows;
    }
    function countallUser($conn)
    {

        $sql = "SELECT * from tbl_user WHERE `is_admin`=0";
        $result = $conn->query($sql);
        return $result->num_rows;
    }

    function allUserFilterSort($sortby, $isblock, $conn)
    {

        $sql = "SELECT * from tbl_user WHERE `isblock`=$isblock AND `is_admin`=0 ORDER BY $sortby";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return $result;
        }
    }
    function allUserSort($sortby, $conn)
    {

        $sql = "SELECT * from tbl_user WHERE `is_admin`=0 ORDER BY $sortby";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return $result;
        }
    }

    function allUserFilterDate($interval, $isblock, $conn)
    {

        $sql = "SELECT * from tbl_user WHERE dateofsignup > DATE_SUB(NOW(), INTERVAL $interval DAY) AND `is_admin`=0 AND `isblock`=$isblock";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return $result;
        }
    }
    function UserFilterDate($interval, $conn)
    {

        $sql = "SELECT * from tbl_user WHERE dateofsignup > DATE_SUB(NOW(), INTERVAL $interval DAY) AND `is_admin`=0 ";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return $result;
        }
    }
}