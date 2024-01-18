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
	<title>HOME PAGE</title>
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
			<a class="nav-link active" href="#">Danh Sách Danh Mục</a>
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
		<h2 class="text-center">Danh Sách Danh Mục</h2>
		<!-- Searching -->
		<div>
			<form method="get">
				<input class="searching" type="text" name="search" id="serach" placeholder="Searching...">
			</form>
		</div>
		<table>
			<thead>
				<tr>
					<th width="50px">STT</th>
					<th>Tên Danh Mục</th>
				</tr>
			</thead>
			<tbody>
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
					$additional = ' and name like "%' . $search . '%" ';
				//Lay danh sach danh muc tu database

				$sql = 'select * from category where 1' . $additional . ' limit ' . $firstIndex . ', ' . $limit;
				$categoryList = executeResultAll($sql); //toàn bộ danh mục -> mảng các mảng (hay mảng các hàng)

				//Neu so luong san pham nhieu --> can toi uu code bằng cách phan trang
				if ($categoryList != false)
					foreach ($categoryList as $item) { //$item là một mảng 
						echo '<tr>
				<td>' . (++$firstIndex) . '</td>
				<td><a class="nav-link category" href = "category.php?id=' . $item['id'] . '">' . $item['name'] . '</a></td>
			</tr>';
					}
				?>
			</tbody>
		</table>
		<?php
		//Phân trang
		$countCategory = countResult('category',$additional);
		$numberPage = ceil($countCategory / $limit);
		pagination($numberPage, $page, $search);
		?>
	</div>
</body>

</html>