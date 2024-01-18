<?php
require_once('config.php');
require_once('dbhelpler.php');

//kiểm tra tài khoản mật khẩu có đúng không
function checkAccount($username, $password)
{
    $sql = 'select id from customer where username = "' . $username . '" and pass = "' . $password . '";';
    $result = executeResultSingle($sql);
    if ($result == false || $result['id'] == null) {
        return false;
    }
    else {
        $_SESSION['id_user'] = $result['id'];
        return true;
    }
}

//Kiểm tra tài khoản đã tồn tại chưa.
function checkAccExist($username)
{
    $sql = 'select id from customer where username = "' . $username . '";';
    $result = executeResultSingle($sql);
    if ($result == false) return false;
    else if($result['id'] == null) return false;
    return true;
}
?>
