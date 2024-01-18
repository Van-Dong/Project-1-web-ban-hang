<?php
session_start();
require_once('../../database/dbhelpler.php');
if (!isset($_SESSION['account']) || $_SESSION['account'] != "admin") {
    header('location: ../../Login/login.php');
    die();
}

//Lưu dữ liệu được gửi tới
$id = $username = $password = $email = '';
if (!empty($_POST)) {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
    }
    if (isset($_POST['username'])) {
        $username = $_POST['username'];
    }
    if (isset($_POST['password'])) {
        $password = $_POST['password'];
    }
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
    }

    if (!empty($username)) {
        $created_at = $updated_at = date('Y-m-d H:s:i');

        //Lưu vào database
        if ($id == '') {
            $sql = 'insert into customer(username, pass, email, created_at, updated_at) values ("' . $username . '", "' . $password . '", "' . $email . '", "' . $created_at . '", "' . $updated_at . '");';
        } else {
            $sql = 'update customer set pass = "' . $password . '", email = "' . $email . '", updated_at = "' . $updated_at . '" where id = ' . $id . ';';
        }

        execute($sql);

        header('Location: index.php');
        die(); //dừng và không xử lý đoạn code dưới nữa.
    }
}

//Nhận id gửi từ trang index
$isReadOnly = false;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = 'select * from customer where id =' . $id . ';';
    $account = executeResultSingle($sql);
    if ($account != false) {
        $username = $account['username'];
        $password = $account['pass'];
        $email = $account['email'];
        $isReadOnly = true;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Quản Lý Tài Khoản</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../icon.ico">
    <link rel="stylesheet" href="../../main.css">
</head>

<body>
    <!-- Thanh điều hướng -->
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="index.php">Quản lý danh mục</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../product/">Quản lý sản phẩm</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../account/">Quản Lý Tài Khoản</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../order/">Quản Lý Đơn Hàng</a>
        </li>
        <li class="nav-item">
            <a href="../../login/logout.php"><button class="btn" type="submit" name="logout">Logout</button></a>
        </li>
    </ul>

    <div class="container">
        <h2 class="text-center">Thêm/Sửa Tài Khoản</h2>

        <!-- Form nhập dữ liệu -->
        <div class="form">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">

                <input type="text" name="id" value="<?= $id ?>" hidden> <!--Phục vụ cho việc debug, người dùng không cần thiết nhìn thấy -->
                <label for="username">USERNAME:</label>
                <input <?= $isReadOnly ? 'readonly' : ''; ?> required="true" type="text" id="username" name="username" value="<?= $username ?>">

                <label for="password">PASSWORD:</label>
                <input required="true" type="text" id="password" name="password" value="<?= $password ?>">

                <label for="email">EMAIL:</label>
                <input required="true" type="email" id="email" name="email" value="<?= $email ?>">
                <button>LƯU</button>
            </form>
        </div>
    </div>
</body>

</html>