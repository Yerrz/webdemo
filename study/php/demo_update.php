<?php
//连接数据库
include("conn.php");

$action = "";
$uuid = "";

$infos = array();
if(isset($_GET['action']) && isset($_GET['uuid'])){
    $action = $_GET['action'];
    $uuid = $_GET['uuid'];
}
if(isset($_POST['action'])){
    $action = $_POST['action'];
}
if($action == "read"){
    $sql = "select * from `form` where `uuid`='$uuid'";
    $query = mysqli_query($db,$sql);
    
    $info= mysqli_fetch_assoc($query);

    $infos["formdata_up"] = $info;

    echo json_encode($infos);

    
}
elseif($action == "update"){
    if(isset($_POST['uuid']) && isset($_POST['ques1']) && isset($_POST['ques2']) && isset($_POST['ques3']) && isset($_POST['ques4']) && isset($_POST['ques5']) && isset($_POST['ques6']) && isset($_POST['ques7'])){
        $uuid = $_POST['uuid'];
        $ques1 = test($_POST['ques1']);
        $ques2 = test($_POST['ques2']);
        $ques3 = test($_POST['ques3']);
        $ques4 = test($_POST['ques4']);
        $ques5 = test($_POST['ques5']);
        $ques6 = test($_POST['ques6']);
        $ques7 = test($_POST['ques7']);

        // 判断ques1是否为数字，ques2是否为全部汉字
        if(is_numeric($ques1) && preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$ques2)){
            $sql = "update `form` set `ques1`='$ques1',`ques2`='$ques2',`ques3`='$ques3',`ques4`='$ques4',`ques5`='$ques5',`ques6`='$ques6',`ques7`='$ques7' where `uuid`='$uuid'";
            mysqli_query($db,$sql);  
            
            echo "update ok!";
            $infos['up_status'] = 200;
        }
        else{
            $infos['up_status'] = 400;
        }
        echo json_encode($infos);
    }
}
else{
    echo "error";
}
//echo json_encode($infos);

function test($data) {
    $data = preg_replace("# #","",$data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
