<?php
session_start();
if (isset($_SESSION['userInfo'])) {
    header('Location: index.php');
}
$_SESSION['activeTime'] = time();
$message = "";
include_once("dbconn.php");
include_once("users.php");

if (isset($_SESSION['cabInfo'])) {
    if (time() - $_SESSION['cabInfo']['time'] > 120) {
        unset($_SESSION['cabInfo']);
    }
}

if (isset($_POST['login'])) {

    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $password = md5($password);

    $user = new user();
    $dbconn = new dbconn();

    $sql = $user->login($username, $password, $dbconn->conn);
    $message = $sql;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/4b2ee26aaa.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <div class="container px-0 mt-3" id="navbar">
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
                        <li class="nav-item active">
                            <a class="btn btn-warning text-dark" href="index.php">Home </a>
                        </li>
                        <li class="nav-item active">
                            <a class="btn btn-info ml-2 text-light" href="signup.php">Sign Up</a>
                        </li>

                    </ul>
                </div>
            </nav>
        </div>
    </header>
    <section id="loginform" class="bg-light-yellow">
        <div id="errors"><?php echo $message; ?></div>
        <div id="login">
            <form action="login.php" method="POST">
                <div>
                    <h2>Login</h2>
                </div>
                <div>
                    <div><label>Username</label></div>
                    <input type="text" id="username" name="username" placeholder="Username" required value="<?php if (isset($_COOKIE['username'])) {
                                                                                                                echo $_COOKIE['username'];
                                                                                                            } ?>">
                </div>
                <div>
                    <div><label>Password</label></div>
                    <input type="password" id="password" name="password" placeholder="password" required>
                </div>
                <div>
                    <input type="submit" id="submit" value="Login" name="login">
                </div>
                <div>
                    <span>Don't have account <a href="signup.php">Signup Here</a></span>
                </div>
            </form>
        </div>

    </section>

    <!-- Footer area -->
    <div class="row text-center container-fluid pt-3 my-0 bg-light" id="footer">


        <div class="col-md-4 col-lg-4 col-sm-12  pt-2">
            <p class="lead text-warning m-0">Ced<span class="bg-warning border-radius text-white px-1">Cab</span></p>
        </div>

        <div class="col-md-4 col-lg-4 col-sm-12 pt-2">
            <p>
                <span>&#169;</span>Copyright 2020
            </p>
        </div>
        <div class="col-md-4 col-lg-4 col-sm-12 pt-2">
            <i class="fa fa-facebook-square icon-size mx-2 " aria-hidden="true"></i>
            <i class="fa fa-twitter icon-size mx-2" aria-hidden="true"></i>
            <i class="fa fa-instagram icon-size mx-2" aria-hidden="true"></i>
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