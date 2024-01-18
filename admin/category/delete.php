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
                $sql = 'delete from category where id = '.$id.';';
                execute($sql);
        }
    }
    header('Location: index.php');
?>