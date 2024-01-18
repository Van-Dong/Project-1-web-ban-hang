<?php
session_start();
require_once('../database/dbhelpler.php');
require_once('../common/utility.php');

if (!isset($_SESSION['account'])) {
    header('Location: ../Login/login.php');
    die();
} else if ($_SESSION['account'] == "admin") {
    header('Location: ../admin/category/index.php');
    die();
}

//Lưu dữ liệu được gửi tới
$id = $name = '';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = 'select name from category where id = ' . $id;
    $categoryName = executeResultSingle($sql);
    if ($categoryName != false) {
        $name = $categoryName['name'];
    }
} else {
    die();
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Category - <?= $name ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../icon.ico">
    <link rel="stylesheet" href="../main.css">
</head>

<body>
    <!-- Thanh điều hướng -->
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="home.php">Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php">Danh Sách Danh Mục</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="./cart/cart.php">Giỏ Hàng Của Bạn <?php quantityProductInCart(); ?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="order.php">Đơn Hàng Của Bạn</a>
        </li>
        <li class="nav-item">
            <a href="../login/logout.php">
                <button class="btn" type="submit" name="logout">Logout</button>
            </a>
        </li>
    </ul>
    <div class="container">
        <h2 class="text-center"><?= $name ?></h2>
        <form method="get">
            <input type="text" name="id" value="<?= $id ?>" hidden>

            <input class="searching" type="text" name="search" id="serach" placeholder="Searching...">

            <button hidden></button>
        </form>

        <div class="product-list">
            <?php
            //Mỗi trang tối đa 10 sản phẩm
            $limit = 10;
            $page = 1;

            if (isset($_GET['page'])) {
                $page = $_GET['page'];
                if ($page < 1) $page = 1;
            }

            $firstIndex = ($page - 1) * $limit;

            $search = '';
            if (isset($_GET['search']))
                $search = $_GET['search'];

            $additional = '';
            if (!empty($search))
                $additional = ' and title like "%' . $search . '%" ';

            $additional = $additional . 'and id_category = ' . $id;

            //Lay danh sach danh muc tu database
            $sql = 'select * from product where 1 ' . $additional . ' limit ' . $firstIndex . ', ' . $limit;;

            $productList = executeResultAll($sql);

            if ($productList != false)
                foreach ($productList as $item) { //$item là một mảng 
                    echo '<div class="product-item">
                <a href="detail.php?id=' . $item['id'] . '">
                    <img style="height: 150px; max-width: 250px" src="' . $item['thumbnail'] . '"/>
                </a>
                <a class="item-title" href="detail.php?id=' . $item['id'] . '">
                    <p style="font-weight: bold">' . $item['title'] . '</p>
                </a>
                <a class="item-price" href="detail.php?id=' . $item['id'] . '">
                    <p style="color: red">' . $item['price'] . '</p>
                </a>
			</div>';
                }

            //Phân trang
            $countResult = countResult('product', $additional);
            $numberPage = ceil($countResult / $limit);
            pagination($numberPage, $page, '&id=' . $id . '&search=' . $search);
            ?>
        </div>
    </div>
</body>

</html>