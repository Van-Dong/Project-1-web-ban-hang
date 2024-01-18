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

//Lấy dữ liệu gửi tới
$id_product = $name = '';
if (isset($_GET['id'])) {
	$id_product = $_GET['id'];
	$sql = 'select * from product where id = ' . $id_product;
	$product = executeResultSingle($sql);
	if ($product == false) {
		die();
	}
} else {
	die();
}

?>
<!DOCTYPE html>
<html>

<head>
	<title>Product - <?= $product['title'] ?></title>
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
				<h2 class="text-center"><?= $product['title'] ?></h2>
				<div class="product-detail">
					<?php echo 
						'<img alt="'.$product['title'].'" src="'.$product['thumbnail'].'" style="max-width: 50%">
						<p class="price">Giá bán: ' . $product['price'] . 'đ</p>
						<div>
						<a class="btn-buy" href="./cart/addtocart.php?id='.$id_product.'">Đặt Mua</a>
						</div>
						<p class="product-detail content">'.$product["content"].'</p>';
					?>
				</div>
	</div>
</body>

</html>