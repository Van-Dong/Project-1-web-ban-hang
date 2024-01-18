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
            <a class="nav-link active" href="#">Quản Lý Tài Khoản</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../order/">Quản Lý Đơn Hàng</a>
        </li>
        <li class="nav-item">
            <a href="../../Login/logout.php"><button class="btn" type="submit" name="logout">Logout</button></a>
        </li>
    </ul>

    <div class="container">
        <h2 class="text-center">Quản Lý Tài Khoản</h2>
        <div>
            <a href="add.php">
                <button class="btn btn-add">Thêm Tài Khoản</button>
            </a>

            <!-- Searching -->
            <div>
                <form method="get">
                    <input class="searching" type="text" name="search" id="serach" placeholder="Searching...">
                </form>
            </div>

            <!-- Bảng danh sách tài khoản -->
            <table>
                <thead>
                    <tr>
                        <th width="50px">STT</th>
                        <th>Tên Đăng Nhập</th>
                        <th>Mật Khẩu</th>
                        <th>Email</th>
                        <th width="50px"></th>
                        <th width="50px"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    //Lấy danh sách tài khoản từ database, mỗi lần tối đa 10 tài khoản
                    $limit = 10;
                    $page = 1;

                    //Lấy page cần đến
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
                        $additional = ' and username like "%' . $search . '%" ';

                    //lệnh lấy tất cả nội dung thỏa mãn từ csdl
                    $sql = 'select * from customer where 1' . $additional . ' limit ' . $firstIndex . ', ' . $limit . ';';
                    $accountList = executeResultAll($sql);

                    if ($accountList != false)
                        foreach ($accountList as $item) { //$item là một mảng 
                            echo '<tr>
				<td>' . (++$firstIndex) . '</td>
				<td>' . $item['username'] . '</td>
                <td>' . $item['pass'] . '</td>
                <td>' . $item['email'] . '</td>
				<td>
					<a href="add.php?id=' . $item['id'] . '"><button class="btn btn-warning">Sửa</button></a>
				</td>
				<td>' . (($item['id'] == 1) ? '' : '
					<a href="delete.php?id=' . $item['id'] . '"><button class="btn btn-danger">Xóa</button></a>
					
				</td>
			</tr>');
                        }
                    ?>
                </tbody>
            </table>

            <!--Phân trang -->
            <?php
            $countResult = countResult('customer', $additional);
            $numberPage = ceil($countResult / $limit);
            pagination($numberPage, $page, $search);
            ?>
        </div>
    </div>
</body>

</html>