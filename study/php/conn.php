<?php

header('Content-Type:text/html;charset=utf-8');//设置页面编码格式为UTF-8
$server = "localhost";
$db_username = "root";
$db_password = "123456";
$db_name = "demo";

$db = mysqli_connect($server, $db_username, $db_password, $db_name);
$db->set_charset("utf8");
if($db->connect_error){
    die("连接失败".$db->connect_error);
}
// else{
//     echo "连接成功!";
// }

