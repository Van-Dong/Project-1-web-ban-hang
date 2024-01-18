<?php
session_start();
require_once('../Database/dbhelpler.php');
require_once('../Database/TestAccount.php');
if (isset($_SESSION['account'])) {
    header("location: login.php");
    die();
}
$pass = $confirm = $username = $email =  $err = '';
if (!empty($_POST)) {
    if (isset($_POST['username'])) {
        $username = $_POST['username'];

        //check exist of account, compare pass and confirm
        if (checkAccExist($username) == true) $err = '<p style="color:red">Username was exist!</p>';
        else {
            $pass = $_POST['pass'];
            $confim = $_POST['confirm'];
            if ($pass === $confim) {
                $email = $_POST['email'];
                $created_at = $updated_at = date('Y-m-d H:i:s');
                $sql = 'insert into customer(username, pass, email, created_at, updated_at) 
                values ("' . $username . '","' . $pass . '","' . $email . '","' . $created_at . '","' . $updated_at . '");';
                execute($sql);
                $_SESSION['account'] = $username;
                header("Location: login.php");
            } else $err = '<p style="color:red">Confirm password was incorrect!</p>';
        }
    }
}
?>



<!DOCTYPE html>
<html lang="vi">

<head>
    <title>Login</title>
    <meta charset="UTF=8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="../icon.ico">
    <link rel="stylesheet" href="../main.css" />
</head>

<body>
    <div class="login-page">
        <div class="form">
            <form method="POST">
                <?=$err?>
                <input required=true type="text" name="username" id="username" placeholder="Username"> </br>

                <input required=true type="text" name="email" id="email" placeholder="Email"> </br>

                <input required=true type="password" name="pass" id="pass" placeholder="Password" /> </br>
                
                <input required=true type="password" name="confirm" id="confirm" placeholder="Confirm password" /> </br>
                <button>CREATE ACCOUNT</button>
            </form>
            <p>You had account? <a href="login.php">Sign in</button></a></p>
        </div>
    </div>
</body>

</html>