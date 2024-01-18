<?php
session_start();
require_once('../../database/dbhelpler.php');
require_once('../../common/utility.php');

if (!isset($_SESSION['account']) || $_SESSION['account'] != "admin") {
	header('Location: ../../Login/login.php');
	die();
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Quản Lý Tài Khoản</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="../../icon.ico">
	<link rel="stylesheet" href="../../main.css" />
</head>

<body>
	<!-- Thanh điều hướng -->
	<ul class="nav">
		<li class="nav-item">
			<a class="nav-link" href="../category/">Quản Lý Danh Mục</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="../product/">Quản Lý Sản Phẩm</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="../account/">Quản Lý Tài Khoản</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="index.php">Quản Lý Đơn Hàng</a>
		</li>
		<li class="nav-item">
			<a href="../../Login/logout.php"><button class="btn">Logout</button></a>
		</li>
	</ul>

	<?php
	if (isset($_GET['id'])) {
		$id_order = $_GET['id'];
	} else {
		header('location: index.php');
	}
	?>


	<div class="container">
		<h2 class="text-center">Chi tiết đơn hàng</h2>

		<div class="detail_order">
			<table>
				<thead>
					<tr>
						<th width="50px">STT</th>
						<th>Tên Sản Phẩm</th>
						<th>Hình ảnh</th>
						<th>Giá bán</th>
						<th width="100px">Số lượng</th>
						<th>Tổng Tiền</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$firstIndex = 0;
					$totalAll = 0;
					$sql = "select product.title as title, product.price as price, product.thumbnail as thumbnail, order_item.quantity as quantity 
                            from order_item left join product on id_product = product.id where id_order = $id_order;";
					$productList = executeResultAll($sql);
					if ($productList != false)
						foreach ($productList as $item) {
							$totalMoney = $item['quantity'] * $item['price'];
							$totalAll += $totalMoney;
							echo '
								<tr>
									<td>' . (++$firstIndex) . '</td>
                                    <td>' . $item['title'] . '</td>
									<td><img style="max-width: 100px"alt="' . $item['title'] . '.jpg" width=75px src="' . $item['thumbnail'] . '"/></td>
									<td>' . $item['price'] . '</td>
									<td>' . $item['quantity'] . '</td>
									<td>' . $totalMoney . '</td>
								</tr>';
						}
					echo '<tr>
							<td></td>
							<td colspan="4">Tổng tiền đơn hàng (không tính ship): </td>
							<td colspan="2"><b><span>' . $totalAll . '</b></span></td>
						  </tr>';
					?>


				</tbody>
			</table>
			<div class="btn-back">
				<a href="index.php">Quay lại trang</a>
			</div>

		</div>
	</div>