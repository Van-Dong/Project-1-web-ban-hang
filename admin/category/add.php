<?php
session_start();
require_once('../../database/dbhelpler.php');
if (!isset($_SESSION['account']) || $_SESSION['account'] != "admin") {
    header('location: ../../Login/login.php');
    die();
}

//Lưu dữ liệu gửi tới
$id = $name = '';
if (!empty($_POST)) {
    if (isset($_POST['name'])) {
        $name = $_POST['name'];
    }
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
    }
    if (!empty($name)) {
        $created_at = $updated_at = date('Y-m-d H:s:i');
        //Lưu vào database
        if ($id == '') {
            $sql = 'insert into category(name, created_at, updated_at) values ("' . $name . '", "' . $created_at . '", "' . $updated_at . '");';
        } else {
            $sql = 'update category set name = "' . $name . '", updated_at = "' . $updated_at . '" where id = ' . $id . ';';
        }

        execute($sql);

        header('Location: index.php');
        die(); //dừng và không xử lý đoạn code dưới nữa.
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = 'select * from category where id =' . $id . ';';
    $category = executeResultSingle($sql);
    if ($category != false) {
        $name = $category['name'];
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Quản Lý Danh Mục</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../icon.ico">
    <link rel="stylesheet" href="../../main.css">
</head>

<body>
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
        <h2 class="text-center">Thêm/Sửa Danh Mục Sản Phẩm</h2>
        <div class="form">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <label for="name">Tên Danh Mục:</label>
                <input type="text" name="id" value="<?= $id ?>" hidden> <!--Người dùng không cần nhìn thấy-->
                <input required="true" type="text" id="name" name="name" value="<?= $name ?>">
                <button>LƯU</button>
            </form>
        </div>
    </div>
</body>

</html>