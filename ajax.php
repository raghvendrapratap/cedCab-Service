<?php
session_start();
include_once("dbconn.php");
include_once("tableRide.php");
include_once("users.php");
include_once("table_location.php");
$tablelocation = new tablelocation();
$dbconn = new dbconn();
$tableRide = new tableRide();
$user = new user();

if (isset($_POST['action'])) {

  if ($_POST['action'] == "sortRide") {


    $sortby = isset($_POST['sortby']) ? $_POST['sortby'] : '';
    $status = $_POST['status'];
    $customerid = $_POST['customerid'];

    if ($status == "pending") {
      $status = 1;
      if ($sortby == "highfare") {

        $sortBy = "total_fare";
        $sort = 'DESC';
        $result = $tableRide->sortRide($status, $sortBy, $sort, $customerid, $dbconn->conn);
      } elseif ($sortby == "lowfare") {
        $sortBy = "total_fare";
        $sort = 'ASC';
        $result = $tableRide->sortRide($status, $sortBy, $sort, $customerid, $dbconn->conn);
      } elseif ($sortby == "highdistance") {
        $sortBy = "total_distance";
        $sort = 'DESC';
        $result = $tableRide->sortRide($status, $sortBy, $sort, $customerid, $dbconn->conn);
      } elseif ($sortby == "lowdistance") {
        $sortBy = "total_distance";
        $sort = 'ASC';
        $result = $tableRide->sortRide($status, $sortBy, $sort, $customerid, $dbconn->conn);
      } else {
        $result = $tableRide->pendingRide($customerid, $dbconn->conn);
      }
    } elseif ($status == "cancelled") {

      $status = 0;
      if ($sortby == "highfare") {

        $sortBy = "total_fare";
        $sort = 'DESC';
        $result = $tableRide->sortRide($status, $sortBy, $sort, $customerid, $dbconn->conn);
      } elseif ($sortby == "lowfare") {
        $sortBy = "total_fare";
        $sort = 'ASC';
        $result = $tableRide->sortRide($status, $sortBy, $sort, $customerid, $dbconn->conn);
      } elseif ($sortby == "highdistance") {
        $sortBy = "total_distance";
        $sort = 'DESC';
        $result = $tableRide->sortRide($status, $sortBy, $sort, $customerid, $dbconn->conn);
      } elseif ($sortby == "lowdistance") {
        $sortBy = "total_distance";
        $sort = 'ASC';
        $result = $tableRide->sortRide($status, $sortBy, $sort, $customerid, $dbconn->conn);
      } else {
        $result = $tableRide->cancelledRide($customerid, $dbconn->conn);
      }
    } elseif ($status == "completed") {

      $status = 2;
      if ($sortby == "highfare") {

        $sortBy = "total_fare";
        $sort = 'DESC';
        $result = $tableRide->sortRide($status, $sortBy, $sort, $customerid, $dbconn->conn);
      } elseif ($sortby == "lowfare") {
        $sortBy = "total_fare";
        $sort = 'ASC';
        $result = $tableRide->sortRide($status, $sortBy, $sort, $customerid, $dbconn->conn);
      } elseif ($sortby == "highdistance") {
        $sortBy = "total_distance";
        $sort = 'DESC';
        $result = $tableRide->sortRide($status, $sortBy, $sort, $customerid, $dbconn->conn);
      } elseif ($sortby == "lowdistance") {
        $sortBy = "total_distance";
        $sort = 'ASC';
        $result = $tableRide->sortRide($status, $sortBy, $sort, $customerid, $dbconn->conn);
      } else {
        $result = $tableRide->completedRide($customerid, $dbconn->conn);
      }
    } else {

      $status = 0;
      if ($sortby == "highfare") {

        $sortBy = "total_fare";
        $sort = 'DESC';
        $result = $tableRide->sortAllRide($sortBy, $sort, $customerid, $dbconn->conn);
      } elseif ($sortby == "lowfare") {
        $sortBy = "total_fare";
        $sort = 'ASC';
        $result = $tableRide->sortAllRide($sortBy, $sort, $customerid, $dbconn->conn);
      } elseif ($sortby == "highdistance") {
        $sortBy = "total_distance";
        $sort = 'DESC';
        $result = $tableRide->sortAllRide($sortBy, $sort, $customerid, $dbconn->conn);
      } elseif ($sortby == "lowdistance") {
        $sortBy = "total_distance";
        $sort = 'ASC';
        $result = $tableRide->sortAllRide($sortBy, $sort, $customerid, $dbconn->conn);
      } else {
        $result = $tableRide->allRide($customerid, $dbconn->conn);
      }
    }
    $resultArray = array();
    while ($row = $result->fetch_assoc()) {
      array_push($resultArray, $row);
    };

    echo json_encode($resultArray);
  }

  if ($_POST['action'] == "getUserInfo") {
    $customerid = $_POST['customerid'];
    $sql = $user->getUserInfo($customerid, $dbconn->conn);
    echo json_encode($sql);
  }

  if ($_POST['action'] == "updateUser") {
    $customerid = $_POST['customerid'];
    $username = $_POST['username'];
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $sql = $user->updateUser($customerid, $username, $name, $mobile, $dbconn->conn);
    echo $sql;
  }

  if ($_POST['action'] == "changePass") {
    $customerid = $_POST['customerid'];
    $oldpass = $_POST['oldpass'];
    $newpass = $_POST['newpass'];
    $sql = $user->changePass($customerid, $oldpass, $newpass, $dbconn->conn);
    echo $sql;
  }

  if ($_POST['action'] == "sortAdminRide") {
    $sortby = $_POST['sortby'];
    $status = $_POST['status'];

    if ($status == "pending") {
      $status = 1;
      if ($sortby == "highfare") {

        $sortBy = "total_fare";
        $sort = 'DESC';
        $result = $tableRide->sortAdminRide($status, $sortBy, $sort, $dbconn->conn);
      } elseif ($sortby == "lowfare") {
        $sortBy = "total_fare";
        $sort = 'ASC';
        $result = $tableRide->sortAdminRide($status, $sortBy, $sort, $dbconn->conn);
      } elseif ($sortby == "highdistance") {
        $sortBy = "total_distance";
        $sort = 'DESC';
        $result = $tableRide->sortAdminRide($status, $sortBy, $sort, $dbconn->conn);
      } elseif ($sortby == "lowdistance") {
        $sortBy = "total_distance";
        $sort = 'ASC';
        $result = $tableRide->sortAdminRide($status, $sortBy, $sort, $dbconn->conn);
      } else {
        $result = $tableRide->allAdminFilterRide($status, $dbconn->conn);
      }
    } elseif ($status == "cancelled") {

      $status = 0;
      if ($sortby == "highfare") {

        $sortBy = "total_fare";
        $sort = 'DESC';
        $result = $tableRide->sortAdminRide($status, $sortBy, $sort, $dbconn->conn);
      } elseif ($sortby == "lowfare") {
        $sortBy = "total_fare";
        $sort = 'ASC';
        $result = $tableRide->sortAdminRide($status, $sortBy, $sort, $dbconn->conn);
      } elseif ($sortby == "highdistance") {
        $sortBy = "total_distance";
        $sort = 'DESC';
        $result = $tableRide->sortAdminRide($status, $sortBy, $sort, $dbconn->conn);
      } elseif ($sortby == "lowdistance") {
        $sortBy = "total_distance";
        $sort = 'ASC';
        $result = $tableRide->sortAdminRide($status, $sortBy, $sort, $dbconn->conn);
      } else {
        $result = $tableRide->allAdminFilterRide($status, $dbconn->conn);
      }
    } elseif ($status == "completed") {

      $status = 2;
      if ($sortby == "highfare") {

        $sortBy = "total_fare";
        $sort = 'DESC';
        $result = $tableRide->sortAdminRide($status, $sortBy, $sort, $dbconn->conn);
      } elseif ($sortby == "lowfare") {
        $sortBy = "total_fare";
        $sort = 'ASC';
        $result = $tableRide->sortAdminRide($status, $sortBy, $sort, $dbconn->conn);
      } elseif ($sortby == "highdistance") {
        $sortBy = "total_distance";
        $sort = 'DESC';
        $result = $tableRide->sortAdminRide($status, $sortBy, $sort, $dbconn->conn);
      } elseif ($sortby == "lowdistance") {
        $sortBy = "total_distance";
        $sort = 'ASC';
        $result = $tableRide->sortAdminRide($status, $sortBy, $sort, $dbconn->conn);
      } else {
        $result = $tableRide->allAdminFilterRide($status, $dbconn->conn);
      }
    } else {

      if ($sortby == "highfare") {

        $sortBy = "total_fare";
        $sort = 'DESC';
        $result = $tableRide->sortAllAdminRide($sortBy, $sort, $dbconn->conn);
      } elseif ($sortby == "lowfare") {
        $sortBy = "total_fare";
        $sort = 'ASC';
        $result = $tableRide->sortAllAdminRide($sortBy, $sort, $dbconn->conn);
      } elseif ($sortby == "highdistance") {
        $sortBy = "total_distance";
        $sort = 'DESC';
        $result = $tableRide->sortAllAdminRide($sortBy, $sort, $dbconn->conn);
      } elseif ($sortby == "lowdistance") {
        $sortBy = "total_distance";
        $sort = 'ASC';
        $result = $tableRide->sortAllAdminRide($sortBy, $sort, $dbconn->conn);
      } else {
        $result = $tableRide->allAdminRide($dbconn->conn);
      }
    }
    $resultArray = array();
    while ($row = $result->fetch_assoc()) {
      $user_id = $row['customer_user_id'];
      $userInfo = $user->getUserInfo($user_id, $dbconn->conn);
      array_push($row, $userInfo);
      array_push($resultArray, $row);
    };

    echo json_encode($resultArray);
  }

  if ($_POST['action'] == "filterbydateAdminRide") {


    $filterbydate = isset($_POST['filterbydate']) ? $_POST['filterbydate'] : '';
    $status = $_POST['status'];

    if ($status == "pending") {
      $status = 1;
      if ($filterbydate == "today") {
        $interval = 1;
        // $sortBy = "total_fare";
        // $sort = 'DESC';
        $result = $tableRide->filterbydateRide($status, $interval, $dbconn->conn);
      } elseif ($filterbydate == "last7days") {
        $interval = 7;
        $result = $tableRide->filterbydateRide($status, $interval, $dbconn->conn);
      } elseif ($filterbydate == "last30days") {
        $interval = 30;
        $result = $tableRide->filterbydateRide($status, $interval, $dbconn->conn);
      } else {
        $result = $tableRide->allAdminFilterRide($status, $dbconn->conn);
      }
    } elseif ($status == "cancelled") {
      $status = 0;
      if ($filterbydate == "today") {
        $interval = 1;
        $result = $tableRide->filterbydateRide($status, $interval, $dbconn->conn);
      } elseif ($filterbydate == "last7days") {
        $interval = 7;
        $result = $tableRide->filterbydateRide($status, $interval, $dbconn->conn);
      } elseif ($filterbydate == "last30days") {
        $interval = 30;
        $result = $tableRide->filterbydateRide($status, $interval, $dbconn->conn);
      } else {
        $result = $tableRide->allAdminFilterRide($status, $dbconn->conn);
      }
    } elseif ($status == "completed") {
      $status = 2;
      if ($filterbydate == "today") {
        $interval = 1;
        $result = $tableRide->filterbydateRide($status, $interval, $dbconn->conn);
      } elseif ($filterbydate == "last7days") {
        $interval = 7;
        $result = $tableRide->filterbydateRide($status, $interval, $dbconn->conn);
      } elseif ($filterbydate == "last30days") {
        $interval = 30;
        $result = $tableRide->filterbydateRide($status, $interval, $dbconn->conn);
      } else {
        $result = $tableRide->allAdminFilterRide($status, $dbconn->conn);
      }
    } else {
      if ($filterbydate == "today") {
        $interval = 1;
        $result = $tableRide->filterbydateRideAll($interval, $dbconn->conn);
      } elseif ($filterbydate == "last7days") {
        $interval = 7;
        $result = $tableRide->filterbydateRideAll($interval, $dbconn->conn);
      } elseif ($filterbydate == "last30days") {
        $interval = 30;
        $result = $tableRide->filterbydateRideAll($interval, $dbconn->conn);
      } else {
        $result = $tableRide->allAdminRide($dbconn->conn);
      }
    }

    $resultArray = array();
    if (isset($result)) {
      while ($row = $result->fetch_assoc()) {
        $user_id = $row['customer_user_id'];
        $userInfo = $user->getUserInfo($user_id, $dbconn->conn);
        array_push($row, $userInfo);
        array_push($resultArray, $row);
      };
    }
    echo json_encode($resultArray);
  }

  if ($_POST['action'] == "filterbydateRide") {

    $customerid = isset($_POST['customerid']) ? $_POST['customerid'] : '';
    $filterbydate = isset($_POST['filterbydate']) ? $_POST['filterbydate'] : '';
    $status = $_POST['status'];

    if ($status == "pending") {
      $status = 1;
      if ($filterbydate == "today") {
        $interval = 1;
        // $sortBy = "total_fare";
        // $sort = 'DESC';
        $result = $tableRide->filterbydateuserRide($customerid, $status, $interval, $dbconn->conn);
      } elseif ($filterbydate == "last7days") {
        $interval = 7;
        $result = $tableRide->filterbydateuserRide($customerid, $status, $interval, $dbconn->conn);
      } elseif ($filterbydate == "last30days") {
        $interval = 30;
        $result = $tableRide->filterbydateuserRide($customerid, $status, $interval, $dbconn->conn);
      } else {
        $result = $tableRide->pendingRide($customerid, $dbconn->conn);
      }
    } elseif ($status == "cancelled") {
      $status = 0;
      if ($filterbydate == "today") {
        $interval = 1;
        $result = $tableRide->filterbydateuserRide($customerid, $status, $interval, $dbconn->conn);
      } elseif ($filterbydate == "last7days") {
        $interval = 7;
        $result = $tableRide->filterbydateuserRide($customerid, $status, $interval, $dbconn->conn);
      } elseif ($filterbydate == "last30days") {
        $interval = 30;
        $result = $tableRide->filterbydateuserRide($customerid, $status, $interval, $dbconn->conn);
      } else {
        $result = $tableRide->cancelledRide($status, $dbconn->conn);
      }
    } elseif ($status == "completed") {
      $status = 2;
      if ($filterbydate == "today") {
        $interval = 1;
        $result = $tableRide->filterbydateuserRide($customerid, $status, $interval, $dbconn->conn);
      } elseif ($filterbydate == "last7days") {
        $interval = 7;
        $result = $tableRide->filterbydateuserRide($customerid, $status, $interval, $dbconn->conn);
      } elseif ($filterbydate == "last30days") {
        $interval = 30;
        $result = $tableRide->filterbydateuserRide($customerid, $status, $interval, $dbconn->conn);
      } else {
        $result = $tableRide->completedRide($status, $dbconn->conn);
      }
    } else {
      if ($filterbydate == "today") {
        $interval = 1;
        $result = $tableRide->filterbydateUserRideAll($customerid, $interval, $dbconn->conn);
      } elseif ($filterbydate == "last7days") {
        $interval = 7;
        $result = $tableRide->filterbydateUserRideAll($customerid, $interval, $dbconn->conn);
      } elseif ($filterbydate == "last30days") {
        $interval = 30;
        $result = $tableRide->filterbydateUserRideAll($customerid, $interval, $dbconn->conn);
      } else {
        $result = $tableRide->allRide($customerid, $dbconn->conn);
      }
    }

    $resultArray = array();
    if (isset($result)) {
      while ($row = $result->fetch_assoc()) {
        $user_id = $row['customer_user_id'];
        $userInfo = $user->getUserInfo($user_id, $dbconn->conn);
        array_push($row, $userInfo);
        array_push($resultArray, $row);
      };
    }
    echo json_encode($resultArray);
  }


  if ($_POST['action'] == "checkUser") {

    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $userInfo = $user->checkUsername($username, $dbconn->conn);

    if (isset($userInfo)) {
      echo "Valid";
    }
  }

  if ($_POST['action'] == "checklocation") {

    $lname = isset($_POST['lname']) ? $_POST['lname'] : '';
    $lInfo = $tablelocation->checklname($lname, $dbconn->conn);

    if (isset($lInfo)) {
      echo "inValid";
    }
  }
  if ($_POST['action'] == "checkupdatelocation") {

    $locationid = isset($_POST['locationid']) ? $_POST['locationid'] : '';
    $updatename = isset($_POST['updatename']) ? $_POST['updatename'] : '';
    $lInfo = $tablelocation->checklupdatename($locationid, $updatename, $dbconn->conn);

    if (isset($lInfo)) {
      echo "inValid";
    }
  }
}