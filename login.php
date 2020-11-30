<?php
session_start();
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
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div>
        <a href="index.php">
            <p class="logopara" id="loginp">Ced<span class="logospan border-radius">Cab</span>
            </p>
        </a>
    </div>
    <section id="loginform">
        <div id="errors"><?php echo $message; ?></div>
        <div id="login">
            <form action="login.php" method="POST">
                <div>
                    <h2>Login</h2>
                </div>
                <div>
                    <div><label>Username</label></div>
                    <input type="text" id="username" name="username" placeholder="Username" required>
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