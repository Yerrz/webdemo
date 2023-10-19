<?php
$code = "";
if(isset($_REQUEST['code'])){
    $code = $_REQUEST['code'];
}
echo json_encode($code);

//产生唯一标识
$unique_id = uniqid();
echo $unique_id;