<?php
include "conn.php";
$page = $_GET['page'];
$offset = 10;
$orgin = ($page - 1) * $offset;
$sql = "SELECT * FROM roa_pdm_bookx limit " . $orgin . "," . $offset;
$query = mysqli_query($db, $sql);
while ($info = mysqli_fetch_array($query)) {
    $sContentBrief = substr($info["sContentBrief"], 0, 500);
    $data .= "<div class='div_book'><a href='bb.php'>书名：{$info['sBookName']}</a><p>作者：{$info["author"]}</p></div>";
}

printf('{ "msg":"S", "data":"%s" }', $data);
?>
