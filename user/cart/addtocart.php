<?php
session_start();
$id_product = $_GET['id'];

if(isset($_SESSION['cart'][$id_product])) {
    $_SESSION['cart'][$id_product] += 1;
} else {
    $_SESSION['cart'][$id_product] = 1;
}

header('location: ../detail.php?id='.$id_product)

//Thêm ký hiệu giỏ hàng vào góc trái
?>

