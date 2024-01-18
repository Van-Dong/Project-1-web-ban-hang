<?php
session_start();
require "../Database/TestAccount.php";
$err = '';
if ($_SERVER["REQUEST_METHOD"]  == "POST") {
    //filter and sanitize
    $account = test_input($_POST["account"]);
    $password = test_input($_POST["pass"]);

    //compare account in database
    //if exist account --> create session
    if (checkAccount($account, $password) == true) {
        $_SESSION["account"] = $account;
    } else $err = '<p style="color:red">Account or password was incorrect!</p>'; //

}
if (isset($_SESSION["account"])) {
    if ($account == "admin") header("location:../admin/category/index.php");
    else header("location: ../user/home.php");
}

function test_input($data)
{
    $data = trim($data); //delete space, tab, /n
    $data = stripslashes($data); // delete \
    $data = htmlspecialchars($data); // chuyển các ký tự đặc biệt thành thực thể html
    return $data;
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
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <?= $err ?>

                <input type="text" placeholder="Username" id="account" name="account"></br></br>

                <input type="password" placeholder="Password" id="pass" name="pass"></br></br>

                <button class="submit">LOGIN</button>
                <!-- <input id="login" type="submit" name="login" value="LOGIN"> -->
            </form>
            <p>Don't have account? <a id="signup" href="signup.php">Create an account</button></a></p>
        </div>
    </div>
    <!-- chức năng, kết nối cơ sở dữ liệu như thế nào, csdl gì.... -->

</body>

</html>