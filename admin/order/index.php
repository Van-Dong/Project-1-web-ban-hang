<?php
session_start();
require_once('../../database/dbhelpler.php');
require_once('../../common/utility.php');

if (!isset($_SESSION['account']) || $_SESSION['account'] != "admin") {
    header('Location: ../../Login/login.php');
    die();
}

//Cập nhật trạng thái đơn hàng:
if (isset($_POST['submit'])) {
    $id_order = $_POST['id'];

    $name_status_order = 'status_order_' . $id_order;
    $status = $_POST[$name_status_order];
    $updated_at = date('Y-m-d H:s:i');

    $sql = "update orderlist set status_order = '$status', updated_at = '$updated_at' where id = $id_order;";
    execute($sql);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Quản Lý Đơn Hàng</title>
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
            <a class="nav-link active" href="#">Quản Lý Đơn Hàng</a>
        </li>
        <li class="nav-item">
            <a href="../../Login/logout.php"><button class="btn" type="submit" name="logout">Logout</button></a>
        </li>
    </ul>

    <div class="container">
        <h2 class="text-center">Quản Lý Đơn Hàng</h2>
        <div>

            <!-- Searching -->
            <div>
                <form method="get">
                    <input class="searching" type="text" name="search" id="serach" placeholder="Searching by id or name">
                </form>
            </div>


            <table>
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Tình trạng</th>
                        <th>Khách Hàng</th>
                        <th>Địa chỉ giao hàng</th>
                        <th>Điện thoại</th>
                        <th>Tổng Tiền</th>
                        <th>Ngày lập đơn</th>
                        <th>Chi tiết đơn hàng</th>
                        <th></th>
                        <th></th>
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
                    if (!empty($search)) {
                        if (is_numeric($search)) {
                            $additional = ' and  id = ' . $search . ' ';
                        } else {
                            $additional = " and full_name like '%$search%' ";
                        }
                    }


                    //lệnh lấy tất cả nội dung thỏa mãn từ csdl
                    $sql = 'select * from orderlist where 1' . $additional . ' order by updated_at desc limit ' . $firstIndex . ', ' . $limit . ';';
                    $orderList = executeResultAll($sql);

                    if ($orderList != false)
                        foreach ($orderList as $order) { //$order là một mảng 
                            $name_status_order = 'status_order_' . $order['id'];
                            //status_order html
                            $htmlStatus = ' <form method="post">
                                            <select class="status" name="' . $name_status_order . '"> ';
                            $selected = $order['status_order'];
                            $arrayStatus = ["Chờ xử lý", "Đang đóng gói", "Chờ thu gom", "Đang chuyển", "Thất bại", "Thành công", "Hủy đơn"];
                            foreach ($arrayStatus as $status) {
                                if ($status == $selected) {
                                    $htmlStatus = $htmlStatus . '<option value="' . $status . '" selected>' . $status . '</option>';
                                } else {
                                    $htmlStatus = $htmlStatus . '<option value="' . $status . '">' . $status . '</option>';
                                }
                            }
                            $htmlStatus = $htmlStatus . '</select>' . '<input type="number" name = "id" value="' . $order['id'] . '" hidden>';


                            echo '<tr>
				<td>' . $order['id'] . '</td>
                <td>' . $htmlStatus . '</td>
                <td>' . $order['full_name'] . '</td>
                <td>' . $order['delivery_address'] . '</td>
                <td>' . $order['phone'] . '</td>
                <td>' . $order['total_money'] . '</td>
                <td>' . $order['created_at'] . '</td>
                <td><a class="detail-order" href="detailorder.php?id=' . $order['id'] . '">Xem</a></td>
				<td>
					<button name="submit" class="btn btn-warning">Cập Nhật</button>
				</td>
                
                </form>
				<td>
					<a href="delete.php?id=' . $order['id'] . '"><button class="btn btn-danger">Xóa</button></a>
					
				</td>
			</tr>';
                        }

                    ?>
                </tbody>
            </table>

            <!--Phân trang -->
            <?php
            $countResult = countResult('orderlist', $additional);
            $numberPage = ceil($countResult / $limit);
            pagination($numberPage, $page, $search);
            ?>
        </div>
    </div>
</body>

</html>