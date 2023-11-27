<?php
header('Content-Type:text/html;charset=utf-8');//设置页面编码格式为UTF-8

date_default_timezone_set("Asia/Shanghai");
$time = time();
$date = date("Y-m-d H:i:s",$time);
echo $date;

$lis = array("\u65b0\u589e","\u6ca1\u6709\u201c5\u201d\u7684\u6570\u5b57\u738b\u56fd","\u65b9\u65b9\u5706\u5706","\u201c\u5c0f\u5c0f\u8499\u53f0\u68ad\u5229\u4e4b\u6570\u5b66\u201d\u7cfb\u5217\uff1a10\u4ee5\u5185\u7684\u8fd0\u7b97","\u6570\u5b57\u7684\u6545\u4e8b1-10","\u201c\u6570\u5b66\u542f\u8499\u5c0f\u6545\u4e8b\u201d\u7cfb\u5217\uff1a\u9664\u6cd5");
echo json_encode($lis);

$sdetail = array();
$sdetail[0] = "项目状态";
$sdetail[1] = 23;
$sdetail[2] = "nihao";
if(!empty($sdetail)){
    var_dump($sdetail);
}

// explode分隔字符串，成数组
$aa = "骨干,你好,hh";
$lis = explode(",",$aa);
if(in_array("骨干h",$lis)){
    echo "yes";
}
print_r($lis);

print_r($_SERVER['QUERY_STRING']);

// join拆分数组为字符串
$array_join = array("aaa","bbb","ccc");
$str_join = join(",",$array_join);
echo "<br>join拆分后：".$str_join;

// strpos查找一个字符串在另一个字符串中是否存在，存在并返回第一个匹配的位置
echo "<br>";
echo  strpos("You love php, I love php too!","php");
echo "<br>";
if(strpos('编辑,骨干编辑,客户,顾客,老板', '骨干编辑') !== false) {
    echo'checked="checked"';
}

?>