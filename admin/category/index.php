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
	<title>Quản Lý Danh Mục</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="../../icon.ico">
	<link rel="stylesheet" href="../../main.css" />
</head>

<body>
	<!-- Thanh điều hướng -->
	<ul class="nav">
		<li class="nav-item">
			<a class="nav-link active" href="#">Quản Lý Danh Mục</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="../product/">Quản Lý Sản Phẩm</a>
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
		<h2 class="text-center">Quản Lý Danh Mục</h2>
		<div>
			<a href="add.php">
				<button class="btn btn-add">Thêm Danh Mục</button>
			</a>
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
						<th width="50px"></th>
						<th width="50px"></th>
					</tr>
				</thead>
				<tbody>
					<?php

					//Lay danh sach danh muc tu database mỗi trang gồm 10 danh mục
					$limit = 10;
					$page = 1;

					//lấy page cần đến
					if (isset($_GET['page']))
						$page = $_GET['page'];
					if ($page <= 0) $page = 1;

					$firstIndex = ($page - 1) * $limit;

					//lấy giá trị cần tìm kiếm
					$search = '';
					if (isset($_GET['search']))
						$search = $_GET['search'];

					$additional = '';
					if (!empty($search))
						$additional = ' and name like "%' . $search . '%" ';

					//lệnh lấy tất cả nội dung thỏa mãn từ csdl
					$sql = 'select * from category where 1' . $additional . ' limit ' . $firstIndex . ', ' . $limit . ';';
					$categoryList = executeResultAll($sql);

					if ($categoryList != false)
						foreach ($categoryList as $item) { //$item là một mảng 
							echo '<tr>
				<td>' . (++$firstIndex) . '</td>
				<td>' . $item['name'] . '</td>
				<td>
					<a href="add.php?id=' . $item['id'] . '"><button class="btn btn-warning">Sửa</button></a>
				</td>
				<td>
					<a href="delete.php?id=' . $item['id'] . '"><button class="btn btn-danger">Xóa</button></a>
					
				</td>
			</tr>';
						}
					?>
				</tbody>
			</table>

			<!--Phân trang -->
			<?php
			//đếm số nội dung thỏa mãn
			$countResult = countResult('category', $additional);
			$numberPage = ceil($countResult / $limit);
			pagination($numberPage, $page, $search);
			?>
		</div>
	</div>
</body>

</html>