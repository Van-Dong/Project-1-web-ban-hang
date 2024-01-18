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
?>
<!DOCTYPE html>
<html>

<head>
    <title>Home</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../icon.ico">
    <link rel="stylesheet" href="../main.css">
</head>

<body>
    <!-- Thanh điều hướng -->
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link active" href="#">Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php">Danh Sách Danh Mục</a>
        </li>
        <li class="nav-item">
			<a class="nav-link" href="./cart/cart.php">Giỏ Hàng Của Bạn <?php quantityProductInCart();?></a>
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
        <h2 class="text-center">Danh Sách Sản Phẩm</h2>

        <form method="get">
            <input class="searching" type="text" name="search" id="serach" placeholder="Searching...">
        </form>

        <div class="product-list">
            <?php
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

            //Lay danh sach danh muc tu database
            $sql = 'select * from product where 1'.$additional.' limit ' . $firstIndex . ', ' . $limit;
            $productList = executeResultAll($sql); //toàn bộ danh mục -> mảng các mảng (hay mảng các hàng)

            //Neu so luong san pham nhieu --> can toi uu code bằng cách phan trang
            if ($productList != false) {
                foreach ($productList as $item) { //$item là một mảng 
                    echo '<div class="product-item">
                <a href="detail.php?id=' . $item['id'] . '">
                    <img style="height: 150px; max-width: 100%" src="' . $item['thumbnail'] . '"/>
                </a>
                <a class="item-title" href="detail.php?id=' . $item['id'] . '">
                    <p>' . $item['title'] . '</p>
                </a>
                <a class="item-price" href="detail.php?id=' . $item['id'] . '">
                    <p>' . $item['price'] . '</p>
                </a>
			</div>';
                }
            }

            //Phân trang
            $countProduct = countResult('product',$additional);
            $numberPage = ceil($countProduct / $limit);
            pagination($numberPage, $page);
            ?>
        </div>
</body>

</html>