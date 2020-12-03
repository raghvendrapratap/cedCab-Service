<?php
session_start();
if (isset($_SESSION['userInfo'])) {
    header('Location: index.php');
}
$message = "";
include_once("dbconn.php");
include_once("users.php");

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
    <section id="loginform">
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
</body>

</html>