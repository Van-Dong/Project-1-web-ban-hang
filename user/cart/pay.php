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

<?php
//Thanh toán + up lên csdl
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $totalMoney = $_POST['totalMoney'];
    $created_at = $updated_at = date('Y-m-d H:s:i');

    $lastId = -1;

    if (isset($name) && isset($email) && isset($phone) && isset($address) && isset($totalMoney)) {
        if (isset($_SESSION['cart'])) {

            //update lên bảng orderlist
            $sql = "insert into orderlist(id_account, full_name, email, phone, delivery_address, total_money, status_order, created_at, updated_at) 
                                values (" . getIdUser($_SESSION['account']) . ", '$name', '$email', '$phone', '$address', $totalMoney, 'Chờ xử lý', '$created_at', '$updated_at')";
            execute($sql, $lastId);

            $lastId = (int)$lastId;

            //update lên bảng order_item
            foreach ($_SESSION['cart'] as $id_product => $quantity) {
                $sql = "insert into order_item(id_order, id_product, quantity, created_at, updated_at) 
                            values ($lastId, $id_product, $quantity, '$created_at', '$updated_at');";
                execute($sql);
            }
            unset($_SESSION['cart']);
        }
    }
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
            <a class="nav-link" href="cart.php">Giỏ Hàng Của Bạn <?php quantityProductInCart(); ?></a>
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
        <h2 class="text-center">Xác nhận hóa đơn thanh toán</h2>
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
            <table>
                <thead>
                    <tr>
                        <th width="50px">STT</th>
                        <th>Tên Sản Phẩm</th>
                        <th>Giá bán</th>
                        <th width="100px">Số lượng</th>
                        <th>Thành Tiền</th>
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
                    $sql = "select id, title, price from product where id in ($strId)";
                    $productList = executeResultAll($sql);
                    foreach ($productList as $item) {
                        $quantity = $_SESSION['cart'][$item['id']];
                        $totalMoney = $quantity * $item['price'];
                        $totalAll += $totalMoney;
                        echo '
								<tr>
									<td>' . (++$firstIndex) . '</td>
									<td>' . $item['title'] . '</td>
									<td>' . $item['price'] . '</td>
									<td>' . $quantity . '</td>
									<td>' . $totalMoney . '</td>
								</tr>';
                    }
                    echo '<tr>
							<td></td>
							<td colspan="3">Tổng tiền giỏ hàng: </td>
							<td><b><span>' . $totalAll . '</b></span></td>
						  </tr>';
                    ?>
                </tbody>
            </table>


            <div class="pay">
                <form class="pay-money" method="post">
                    <div class="form-group">
                        <label>Họ tên khách hàng</label><br>
                        <input required type="text" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Địa chỉ email</label><br>
                        <input required type="text" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label><br>
                        <input required type="text" name="phone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Địa chỉ nhận hàng</label><br>
                        <input required type="text" name="address" class="form-control">
                    </div>
                    <div class="form-group" hidden>
                        <label>Địa chỉ nhận hàng</label>
                        <input required type="text" name="totalMoney" class="form-control" value="<?= $totalAll ?>">
                    </div>
                    </div>
            <div class="btn-cart">
            <button name="submit">Thanh toán</button>
            <a href="../home.php">Tiếp tục mua hàng</a>
            </div>
                    
                </form>
            


        </div>
    </div>
</body>

</html>

<!-- Trong thẻ input nếu đặt name = "element_name[]" thì khi gửi đến máy chủ thì máy chủ sẽ nhận được một mảng. Nếu đặt sẵn là element_name[so1] thì 
mảng nhận được là mảng ascciot -->