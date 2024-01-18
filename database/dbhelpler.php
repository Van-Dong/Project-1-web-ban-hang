<?php
require_once('config.php');

//function execute insert, update, delete// no data return;
function execute($sql, &$lastId = -1)
{
    try {
        $conn = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USERNAME, PASSWORD);
        //set mode error
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $row = $conn->exec($sql); 
        if($lastId == -1) {
            $lastId = $conn->lastInsertId();
        }
        $conn = null;
        return $row;
    } catch (PDOException $e) {
        echo $e->getMessage();
        $conn = null;
        return 0;
    }
}
function executeResultAll($sql)
{
    try {
        $conn = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USERNAME, PASSWORD);
        //set mode error
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();
        $conn = null;

        if ($result == false) return false;
        return $result;
    } catch (PDOException $e) {
        echo $e->getMessage();
        $conn = null;

        return false;
    }
}

function executeResultSingle($sql)
{
    try {
        $conn = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USERNAME, PASSWORD);
        //set mode error
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetch();
        $conn = null;

        if ($result == false) return false;
        return $result;
    } catch (PDOException $e) {
        echo $e->getMessage();
        $conn = null;
        return false;
    }
}


function countResult($table, $additional = '') {
    $sql = 'select count(id) as total from '.$table.' where 1 '.$additional;
    $countResult = executeResultSingle($sql);
    if($countResult != false) {
        return $countResult['total'];
    } else {
        return 0;
    }
}
function getIdUser($username) {
    $sql = 'select id from customer where username = "'.$username.'";' ;
    $result = executeResultSingle($sql);
    if($result != false) {
        return $result['id'];
    } else {
        return NULL;
    }
}
?>