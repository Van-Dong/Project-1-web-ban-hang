<?php
session_start();
require_once ('../../database/dbhelpler.php');
if(!isset($_SESSION['account']) || $_SESSION['account'] != "admin"){
	header('location: ../../Login/login.php');
	die();
}

    if(!empty($_GET)) {
        if (isset($_GET['id'])){
                $id = $_GET['id'];
                $sql = "select status_order from orderlist where id = $id";
                $result = executeResultSingle($sql);
                var_dump($result);
                $status = $result['status_order'];
                if($status == "Thành công" || $status == "Hủy đơn") {
                    
                    //Xóa item của đơn hàng trong order_item
                    $sql = "delete from order_item where id_order = $id;" ;
                    execute($sql);

                    //Xóa đơn hàng
                    $sql = 'delete from orderlist where id = '.$id.';';
                    execute($sql);
                } 
        }
    }
    header('Location: index.php');
?>
