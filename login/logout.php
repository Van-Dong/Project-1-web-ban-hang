<?php
session_start();
if (isset($_SESSION["account"])) {
    session_unset();
    session_destroy();
}
header("location: login.php");
?>