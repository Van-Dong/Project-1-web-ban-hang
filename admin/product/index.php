<?php
session_start();
require_once('../../database/dbhelpler.php');
require_once('../../common/utility.php');
if (!isset($_SESSION['account']) || $_SESSION['account'] != "admin") {
	header('location: ../../Login/login.php');
	die();
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
			<a class="nav-link" href="../category/index.php">Quản Lý Danh Mục</a>
		</li>
		<li class="nav-item">
			<a class="nav-link active" href="#">Quản Lý Sản Phẩm</a>
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
		<h2 class="text-center">Quản Lý Sản Phẩm</h2>
		<a href="add.php">
			<button class="btn btn-add">Thêm Sản Phẩm</button>
		</a>
		<!-- searching -->
		<div>
			<form method="get">
				<input class="searching" type="text" name="search" id="serach" placeholder="Searching...">
			</form>
		</div>
		<table>
			<thead>
				<tr>
					<th width="50px">STT</th>
					<th>Hình ảnh</th>
					<th>Tên Sản Phẩm</th>
					<th>Giá bán</th>
					<th>Danh mục</th>
					<th>Ngày cập nhật</th>
					<th width="50px"></th>
					<th width="50px"></th>
				</tr>
			</thead>
			<tbody>
				<?php
				//Lay danh sach danh muc tu database
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
					$additional = ' and title like "%' . $search . '%" ';

				//lệnh lấy tất cả nội dung thỏa mãn từ csdl
				$sql = 'select product.id, product.title, product.price, product.thumbnail, product.updated_at, 
	category.name category_name from product left join category on product.id_category = category.id 
	where 1' . $additional . ' limit ' . $firstIndex . ', ' . $limit . ';';
				$productList = executeResultAll($sql); //toàn bộ danh mục -> mảng các mảng (hay mảng các hàng)

				if ($productList != false)
					foreach ($productList as $item) { //$item là một mảng associative
						echo '<tr>
				<td>' . (++$firstIndex) . '</td>
				<td><img style="max-width: 100px"alt="' . $item['title'] . '.jpg" height=35px width=50px src="' . $item['thumbnail'] . '"/></td>
				<td>' . $item['title'] . '</td>
				<td>' . $item['price'] . '</td>
				<td>' . $item['category_name'] . '</td>
				<td>' . $item['updated_at'] . '</td>
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
		<?php
		//Phân trang
		$countResult = countResult('product', $additional);
		$numberPage = ceil($countResult / $limit);
		pagination($numberPage, $page, $search) ?>
	</div>
</body>

</html>