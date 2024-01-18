<?php
session_start();
require_once('../../database/dbhelpler.php');
if (!isset($_SESSION['account']) || $_SESSION['account'] != "admin") {
    header('location: ../../Login/login.php');
    die();
}

//Lấy dữ liệu gửi tới
$id = $title = $price = $thumbnail = $content = $id_category = '';
if (!empty($_POST)) {
    if (isset($_POST['title'])) {
        $title = $_POST['title'];
        $title = str_replace('"', '\\"', $title);
    }
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
    }
    if (isset($_POST['price'])) {
        $price = $_POST['price'];
    }
    if (isset($_POST['thumbnail'])) {
        $thumbnail = $_POST['thumbnail'];
        $thumbnail = str_replace('"', '\\"', $thumbnail);
    }
    if (isset($_POST['content'])) {
        $content = $_POST['content'];
        $content = str_replace('"', '\\"', $content);
    }
    if (isset($_POST['id_category'])) {
        $id_category = $_POST['id_category'];
    }

    if (!empty($title)) {
        $created_at = $updated_at = date('Y-m-d H:s:i');
        //Lưu vào database
        if ($id == '') {
            $sql = 'insert into product(title, id_category, price, thumbnail, content, created_at, updated_at) 
                values ("' . $title . '", ' . $id_category . ', ' . $price . ', "' . $thumbnail . '", "' . $content . '", "' . $created_at . '", "' . $updated_at . '");';
        } else {
            //Trường hợp sửa
            $sql = 'update product set title = "' . $title . '", id_category = ' . $id_category .
                ', price = ' . $price . ', thumbnail = "' . $thumbnail . '", content = "' . $content . '", updated_at = "' . $updated_at . '" where id = ' . $id . ';';
        }

        execute($sql);

        header('Location: index.php');
        die(); //dừng và không xử lý đoạn code dưới nữa.
    }
}


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = 'select * from product where id =' . $id . ';';
    $product = executeResultSingle($sql);
    if ($product != false) {
        $title = $product['title'];
        $price = $product['price'];
        $thumbnail = $product['thumbnail'];
        $id_category = $product['id_category'];
        $content = $product['content'];
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Quản Lý Sản Phẩm</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../icon.ico">
    <link rel="stylesheet" href="../../main.css">
</head>

<body>
    <!-- Thanh điều hướng -->
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="../category/">Quản lý danh mục</a> <!--Tự động gọi đến file index.php-->
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php">Quản lý sản phẩm</a>
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
        <h2 class="text-center">Thêm/Sửa Sản Phẩm</h2>
        <div class="form group">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">

                <input type="text" name="id" value="<?= $id ?>" hidden>

                <label for="title">Tên Sản Phẩm:</label>
                <input required="true" type="text" id="title" name="title" value="<?= $title ?>" placeholder="Tên sản phẩm">

                <label for="id_category">Chọn Danh Mục:</label>
                <select require="true" id="id_category" name="id_category">

                    <!-- Lấy Dữ liệu từ database -->
                    <?php
                    $sql = "select name, id from category;";
                    $categoryList = executeResultAll($sql);
                    foreach ($categoryList as $item)
                        if ($item['id'] == $id_category)
                            echo '<option selected value=' . $item['id'] . '>' . $item['name'] . '</option>';
                        else
                            echo '<option value=' . $item['id'] . '>' . $item['name'] . '</option>';
                    ?>
                </select>
                <br>

                <label for="price">Giá bán:</label>
                <input required="true" type="text" id="price" name="price" value="<?= $price ?>">

                <label for="thumbnail">Thumbnail:</label>
                <input required="true" type="text" id="thumbnail" name="thumbnail" value="<?= $thumbnail ?>">
                <img style="max-width: 200px" src="<?= $thumbnail ?>"> <br>

                <label for="content">Content:</label>
                <textarea required="true" rows="6" id="content" name="content"><?= $content ?></textarea>

                <button>LƯU</button>
            </form>
        </div>
    </div>
</body>

</html>