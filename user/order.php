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
	<title>Giỏ Hàng</title>
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
			<a class="nav-link active" href="#">Đơn Hàng Của Bạn</a>
		</li>
		<li class="nav-item">
			<a href="../../login/logout.php">
				<button class="btn" type="submit" name="logout">Logout</button>
			</a>
		</li>
	</ul>

	<div class="container">
		<h2 class="text-center">Đơn hàng của bạn</h2>

		<?php
		//Lấy danh sách đơn hàng từ hệ thống ra.
		$idUser = getIdUser($_SESSION['account']);
		$sql = "select id, full_name, total_money, status_order from orderlist where id_account = $idUser order by updated_at desc;";
		$orderList = executeResultAll($sql);
		if ($orderList == false || $orderList == []) {
			echo "Quý khách chưa có đơn hàng nào!";
			die();
		}
		?>
		<div class="cart">
			<table>
				<thead>
					<tr>
						<th width="50px">Mã đơn hàng</th>
						<th>Tên khách hàng</th>
						<th>Số lượng sản phẩm</th>
						<th>Tổng tiền thanh toán (không tính ship)</th>
						<th width="100px">Phí ship</th>
						<th>Trạng thái đơn hàng</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($orderList as $order) {
						$id_order = $order['id'];
						$quantity = executeResultSingle("select count(id) as quantity from order_item where id_order = $id_order;");
						$quantity = $quantity['quantity'];
						echo '
								<tr>
									<td>' . $order['id'] . '</td>
									<td>' . $order['full_name'] . '</td>
									<td>' . $quantity . '</td>
									<td>' . $order['total_money'] . ' đ</td>
									<td>30.000 đ</td>
									<td style="font-style: italic">' . $order['status_order'] . '</td>
								</tr>';
					}
					?>


				</tbody>
			</table>
			<div class="buy-continue">
				<a class="btn-buy" href="home.php">Tiếp tục mua hàng</a>
				<div>

				</div>
			</div>
</body>

</html>