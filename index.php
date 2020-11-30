<?php
session_start();
$errors = "";
include_once("dbconn.php");
include_once("table_location.php");
include_once("tableRide.php");
$tablelocation = new tablelocation();
$dbconn = new dbconn();

if (isset($_POST['book'])) {
    $pickup = isset($_POST['pickup']) ? $_POST['pickup'] : '';
    $drop = isset($_POST['drop']) ? $_POST['drop'] : '';
    $cabType = isset($_POST['cabType']) ? $_POST['cabType'] : '';
    $luggage = isset($_POST['luggage']) ? $_POST['luggage'] : 0;
    $fare = isset($_POST['fare']) ? $_POST['fare'] : 0;
    $distance = isset($_POST['distance']) ? $_POST['distance'] : 0;
    date_default_timezone_set('Asia/Kolkata');
    $dateofride = date('Y-m-d H:i');
    $customerid = isset($_SESSION['userInfo']['customerid']) ? $_SESSION['userInfo']['customerid'] : 0;
    $status = 1;
    $tableRide = new tableRide();

    if (isset($_SESSION['userInfo'])) {

        if ($_SESSION['userInfo']['is_admin'] == 1) {
            echo "<script type='text/javascript'>alert('You are Admin. You cant book Cab.');</script>";
        } elseif ($_SESSION['userInfo']['is_admin'] == 0) {

            $sql = $tableRide->bookRide($pickup, $drop, $cabType, $luggage, $fare, $dateofride, $distance, $status, $customerid, $dbconn->conn);
            $errors = $sql;
        }
    } else {
        $_SESSION['cabInfo'] = array(
            'pickup' => $pickup,
            'drop' =>  $drop,
            'cabType' =>  $cabType,
            'luggage' =>  $luggage,
            'fare' =>  $fare,
            'dateofride' =>  $dateofride,
            'distance' =>  $distance,
            'status' =>  $status,
        );

        echo "<script type='text/javascript'>alert('Please Login First.'); window.location='login.php';</script>";
    }
}
?>

<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <script src="https://kit.fontawesome.com/4b2ee26aaa.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="cab.js"></script>
    <title>Ced Cab</title>
</head>

<body>
    <!-- Header -->
    <header>
        <div class="container px-0" id="navbar">
            <nav class="navbar navbar-expand-lg navbar-expand-md navbar-light bg-white">
                <p class="lead text-warning m-0">Ced<span class="bg-warning border-radius text-white px-1">Cab</span>
                </p>
                <button class="navbar-toggler px-2" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon small-text"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav nav-pills ml-auto">

                        <?php
                        if (isset($_SESSION['userInfo'])) {

                            if ($_SESSION['userInfo']['is_admin'] == 1) { ?>

                        <li class="nav-item active">
                            <a class="btn btn-outline-info m-2 my-sm-0" href="admindashboard.php">Admin Dashboard</a>
                        </li>
                        <li class="nav-item active">
                            <a class="btn btn-danger text-light" href="logout.php">Logout </a>
                        </li>

                        <?php
                            } elseif ($_SESSION['userInfo']['is_admin'] == 0) { ?>

                        <li class="nav-item active">
                            <a class="btn btn-outline-info m-2 my-sm-0" href="customerdashboard.php">Customer
                                Dashboard</a>
                        </li>
                        <li class="nav-item active">
                            <a class="btn btn-danger text-light" href="logout.php">Logout </a>
                        </li>

                        <?php }
                        } else { ?>

                        <li class="nav-item active">
                            <a class="btn btn-success text-light" href="login.php">Login </a>
                        </li>
                        <li class="nav-item active">
                            <a class="btn btn-info ml-2 text-light" href="signup.php">Sign Up</a>
                        </li>

                        <?php  }
                        ?>

                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Container Body / Form Body -->
    <div class="container-fluid p-2 px-5" id="banner">

        <div class="container-fluid text-center text-white">
            <h1>Book a City Taxi to your destination</h1>
            <p class="lead">Choose from a range of categories and prices</p>
        </div>

        <!-- Form to take input -->
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-5 bg-light-grey px-4 pb-2 border-radius">
                <form action="" method="POST">
                    <div class="text-center border-bottom border-secondary mb-2">
                        <p class="lead text-warning my-2">Ced<span
                                class="bg-warning border-radius text-white px-1">Cab</span></p>
                    </div>
                    <div class="text-center">
                        <h4 class=""> Your everyday travel partner</h4>
                        <p>AC Cabs for point to point travel</p>
                    </div>
                    <div class="form-group row bg-grey border-radius my-1">
                        <label for="pickup" class="col-sm-2  small-text my-auto">PICKUP</label>
                        <div class="col-sm-10">
                            <select class="form-control-plaintext pl-2 form-control dropdown-arrow-none options"
                                name="pickup" id="pickup">
                                <option value="" selected>Current location</option>

                                <?php
                                $result = $tablelocation->allLocation($dbconn->conn);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) { ?>

                                <option value="<?php echo $row['name'] ?>"><?php echo $row['name'] ?></option>

                                <?php  }
                                } ?>

                            </select>
                            <small id="pickupMsg1" class="form-text text-danger">
                                *Please Enter Pickup location.
                            </small>
                        </div>
                    </div>
                    <div class="form-group row bg-grey border-radius my-1">
                        <label for="drop" class="col-sm-2 col-form-label small-text my-auto">DROP</label>
                        <div class="col-sm-10 ">
                            <select class="form-control pl-2 form-control-plaintext dropdown-arrow-none options"
                                name="drop" id="drop">
                                <option value="" selected>Enter drop for ride estimate</option>

                                <?php
                                $result = $tablelocation->allLocation($dbconn->conn);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) { ?>

                                <option value="<?php echo $row['name'] ?>"><?php echo $row['name'] ?></option>

                                <?php  }
                                } ?>

                            </select>
                            <small id="dropMsg1" class="form-text text-danger">
                                *Please Enter Drop location.
                            </small>
                        </div>
                    </div>
                    <div class="form-group row bg-grey border-radius my-1">
                        <label for="type" class="col-sm-2 col-form-label small-text my-auto">CAB TYPE</label>
                        <div class="col-sm-10 ">
                            <select class="form-control pl-2 form-control-plaintext dropdown-arrow-none" name="cabType"
                                id="type">
                                <option value="" selected>Drop down to select CAB Type</option>
                                <option value="CedMicro">CedMicro</option>
                                <option value="CedMini">CedMini</option>
                                <option value="CedRoyal">CedRoyal</option>
                                <option value="CedSUV">CedSUV</option>
                            </select>
                            <small id="typeMsg" class="form-text text-danger">
                                *Please Select Cab Type.
                            </small>
                        </div>
                    </div>
                    <div class="form-group row bg-grey border-radius my-1">
                        <label for="luggage" class="col-sm-2 col-form-label small-text my-auto">Luggage</label>
                        <div class="col-sm-10 bg-grey border-radius">
                            <input type="text" class="form-control-plaintext pl-2 " id="luggage"
                                placeholder="Enter Weight in KG">
                            <small id="luggageMsg" class="form-text text-danger">
                                *Luggage option is not available with CedMicro.
                            </small>
                        </div>
                    </div>
                    <div class="form-group row  border-radius my-1">
                        <div class=" col-sm-12 p-0 bg-warning text-centre">
                            <span id="result" class="text-centre "></span>
                            <input type="hidden" name="fare" id="fare">
                            <input type="hidden" name="distance" id="distance">
                            <input type="hidden" name="luggage" id="luggageid">
                        </div>
                    </div>
                    <div class="form-group row bg-grey">
                        <div class=" col-sm-12 m-0 p-0">
                            <input type="button" class=" btn-warning btn-lg btn-block" id="submit" name="submit"
                                value="Calculate Fare">
                        </div>
                    </div>
                    <div class="form-group row bg-grey">
                        <div class=" col-sm-12 m-0 p-0" id="bookNow">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <!-- Footer area -->
    <div class="row text-center container-fluid p-0 m-0 " id="footer">

        <div class="col-md-4 col-lg-4 col-sm-12 pt-2">
            <i class="fa fa-facebook-square icon-size mx-2 " aria-hidden="true"></i>
            <i class="fa fa-twitter icon-size mx-2" aria-hidden="true"></i>
            <i class="fa fa-instagram icon-size mx-2" aria-hidden="true"></i>
        </div>

        <div class="col-md-4 col-lg-4 col-sm-12  pt-2">
            <p class="lead text-warning m-0">Ced<span class="bg-warning border-radius text-white px-1">Cab</span></p>
        </div>
        <div class="col-md-4 col-lg-4 col-sm-12 pt-2">
        </div>
    </div>

    <!-- Libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

</body>

</html>