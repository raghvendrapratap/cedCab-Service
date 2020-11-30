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
    <title>SignUp</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div>
        <a href="index.php">
            <p class="logopara" id="loginp">Ced<span class="logospan border-radius">Cab</span>
            </p>
        </a>
    </div>
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
                    <input type="text" id="username" name="username" placeholder="Username">
                </div>
                <div>
                    <div><label>Name</label></div>
                    <input type="text" id="name" name="name" placeholder="name">
                </div>
                <div>
                    <div><label>Mob No.</label></div>
                    <input type="text" id="mob" name="mob" placeholder="Mob No.">
                </div>
                <div>
                    <div><label>Password</label></div>
                    <input type="password" id="password" name="password" placeholder="password">
                </div>
                <div>
                    <div><label>Re-Password</label></div>
                    <input type="password" id="repassword" name="repassword" placeholder="Re-password">
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