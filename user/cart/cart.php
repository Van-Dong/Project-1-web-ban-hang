<?php
session_start();
require_once('../../database/dbhelpler.php');
require_once('../../common/utility.php');

if (!isset($_SESSION['account'])) {
	header('Location: ../../Login/login.php');
	die();
} else if ($_SESSION['account'] == "admin") {
	header('Location: ../../admin/category/index.php');
	die();
}
?>
<!DOCTYPE html>
<html>

<head>
	<title>Giỏ Hàng</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="../../icon.ico">
	<link rel="stylesheet" href="../../main.css">
</head>

<body>
	<!-- Thanh điều hướng -->
	<ul class="nav">
		<li class="nav-item">
			<a class="nav-link" href="../home.php">Home</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="../index.php">Danh Sách Danh Mục</a>
		</li>
		<li class="nav-item">
			<a class="nav-link active" href="#">Giỏ Hàng Của Bạn <?php quantityProductInCart(); ?></a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="../order.php">Đơn Hàng Của Bạn</a>
		</li>
		<li class="nav-item">
			<a href="../../login/logout.php">
				<button class="btn" type="submit" name="logout">Logout</button>
			</a>
		</li>
	</ul>

	<div class="container">
		<h2 class="text-center">Giỏ Hàng Của Bạn</h2>

		<?php
		if (!isset($_SESSION['cart'])) {
			echo '<p> Trong giỏ hàng của bạn hiện tại không có sản phẩm nào cả!';
			die();
		}
		//
		if (isset($_POST['quantity'])) {
			foreach ($_POST['quantity'] as $id_item => $count) {
				if ($count == 0) {
					unset($_SESSION['cart'][$id_item]);
				} else {
					$_SESSION['cart'][$id_item] = $count;
				}
			}
		}
		?>
		<div class="cart">
			<form id="cart" method="post">
				<table>
					<thead>
						<tr>
							<th width="50px">STT</th>
							<th>Hình ảnh</th>
							<th>Tên Sản Phẩm</th>
							<th>Giá bán</th>
							<th width="100px">Số lượng</th>
							<th>Tổng Tiền</th>
							<th width="50px"></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$firstIndex = 0;
						$totalAll = 0;
						$arrayId = array();
						foreach ($_SESSION['cart'] as $id_product => $quantity) {
							$arrayId[] = $id_product;
						}
						$strId = implode(', ', $arrayId);
						$sql = "select id, title, price, thumbnail from product where id in ($strId);";
						$productList = executeResultAll($sql);
						if ($productList != false)
							foreach ($productList as $item) {
								$quantity = $_SESSION['cart'][$item['id']];
								$totalMoney = $quantity * $item['price'];
								$totalAll += $totalMoney;
								echo '
								<tr>
									<td>' . (++$firstIndex) . '</td>
									<td><img style="max-width: 100px"alt="' . $item['title'] . '.jpg" width=75px src="' . $item['thumbnail'] . '"/></td>
									<td>' . $item['title'] . '</td>
									<td>' . $item['price'] . '</td>
									<td> <input class="quantity" name="quantity[' . $item['id'] . ']" type="number" min="0" value="' . $quantity . '"> </td>
									<td>' . $totalMoney . '</td>
									<td>
										<a style="text-decoration:none" href="deletefromcart.php?id=' . $item['id'] . '">Xóa</a>
									</td>
								</tr>';
							}
						echo '<tr>
							<td></td>
							<td colspan="4">Tổng tiền giỏ hàng: </td>
							<td colspan="2"><b><span>' . $totalAll . '</b></span></td>
						  </tr>';
						?>


					</tbody>

				</table>

				<div class="btn-cart">
					<button type="submit" href="#">Cập Nhật Giỏ Hàng</button>
					<a href="../home.php">Tiếp tục mua hàng</a>
					<a href="deletefromcart.php?id=0">Xóa toàn bộ sản phẩm</a>
					<a href="pay.php">Thanh toán</a>
				</div>
			</form>


		</div>
	</div>
</body>

</html>
