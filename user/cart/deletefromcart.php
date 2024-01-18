<?php
    session_start();
    if(isset($_GET['id'])){
        $id_product = $_GET['id'];
        if($id_product == 0) {
            unset($_SESSION['cart']);
        } else {
            unset($_SESSION['cart'][$id_product]);
            if(count($_SESSION['cart']) == 0) {
                unset($_SESSION['cart']);
            }
        }   
    }
    header('location: cart.php');
    /**
     * Để hủy một biến nào đó lưu trữ trong Session thì dùng lệnh unset ví dụ unset($_SESSION['counter']); 
     * Để hủy toàn bộ Session thì dùng lệnh session_destroy() */
?>