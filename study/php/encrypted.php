<?php
header('Content-type:text/html;charset=utf-8');
if(isset($_POST['encryptedData'])){
    $data = $_POST['encryptedData'];
}
else{
    $data = "no";
}
echo $data;
?>