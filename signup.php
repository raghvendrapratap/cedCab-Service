<?php
session_start();
include_once("dbconn.php");
include_once("users.php");
$errors = array();

if (isset($_POST['signup'])) {

    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $repassword = isset($_POST['repassword']) ? $_POST['repassword'] : '';
    $mob = isset($_POST['mob']) ? $_POST['mob'] : '';
    $isblock = 0;
    $is_admin = 0;
    date_default_timezone_set('Asia/Kolkata');
    $dateofsignup = date('Y-m-d H:i');

    $user = new user();
    $dbconn = new dbconn();

    $sql = $user->signup($username, $name, $dateofsignup, $mob, $isblock, $password, $repassword, $is_admin, $dbconn->conn);
    $errors = $sql;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <title>SignUp</title>
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
                            <a class="btn btn-success ml-2 text-light" href="login.php">Login </a>
                        </li>
                        <li class="nav-item active">
                            <a class="btn btn-info ml-2 text-light" href="signup.php">Sign Up</a>
                        </li>

                    </ul>
                </div>
            </nav>
        </div>
    </header>
    <section id="signupform">
        <div>
            <?php if (sizeof($errors) > 0) : ?>
            <ul id="errors">
                <?php foreach ($errors as $key) : ?>
                <li>*<?php echo $key['msg'] ?>.</li>
                <?php endforeach ?>
            </ul>
            <?php endif ?>
        </div>
        <div id="signup">
            <form action="signup.php" method="POST">
                <div>
                    <h2>Sign Up</h2>
                </div>
                <div>
                    <div><label>Username</label></div>
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <div>
                    <div><label>Name</label></div>
                    <input type="text" id="name" name="name" placeholder="name" required>
                </div>
                <div>
                    <div><label>Mob No.</label></div>
                    <input type="number" id="mob" name="mob" placeholder="Mob No.">
                </div>
                <div>
                    <div><label>Password</label></div>
                    <input type="password" id="password" name="password" placeholder="password" required>
                </div>
                <div>
                    <div><label>Re-Password</label></div>
                    <input type="password" id="repassword" name="repassword" placeholder="Re-password" required>
                </div>
                <div>
                    <input type="submit" id="submit" value="Signup" name="signup">
                </div>
                <div>
                    <span>Already have account <a href="login.php">Login Here</a></span>
                </div>
            </form>
        </div>

    </section>
</body>

</html>