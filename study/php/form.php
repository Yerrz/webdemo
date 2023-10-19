<?php
header('Content-Type:text/html;charset=utf-8');//设置页面编码格式为UTF-8
$server = "127.0.0.1";
$db_username = "root";
$db_password = "";
$db_name = "demo";

$db = mysqli_connect($server, $db_username, $db_password, $db_name);
$db->set_charset("utf8");
if($db->connect_error){
    die("连接失败".$db->connect_error);
}

function test($data) {
    $data = preg_replace("# #","",$data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
// if(!empty($_POST['ques1']) && !empty($_POST['ques2']) && !empty($_POST['ques3']) && !empty($_POST['ques4']) && !empty($_POST['ques5']) && !empty($_POST['ques6']) && !empty($_POST['ques7'])) {
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $unique_id = uniqid();
    $ques1 = test($_POST['ques1']);
    $ques2 = test($_POST['ques2']);
    $ques3 = test($_POST['ques3']);
    $ques4 = test($_POST['ques4']);
    $ques5 = test($_POST['ques5']);
    $ques6 = test($_POST['ques6']);
    $ques7 = test($_POST['ques7']);
    $infos['u_id'] = $unique_id;

    if(is_numeric($ques1) && preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$ques2)){
        $sql = "INSERT INTO `form`(`uuid`,`ques1`, `ques2`, `ques3`, `ques4`, `ques5`, `ques6`, `ques7`) VALUES('$unique_id','$ques1','$ques2','$ques3','$ques4','$ques5','$ques6','$ques7')";
    
        $result = mysqli_query($db,$sql);
        if (!$result){  
            die('Error: ' . mysqli_connect_error());
        }
        $infos['status'] = 200;
    }
    else{
        $infos['status'] = 400;
    }
    // $sql = "INSERT INTO `form`(`uuid`,`ques1`, `ques2`, `ques3`, `ques4`, `ques5`, `ques6`, `ques7`) VALUES('$unique_id','$ques1','$ques2','$ques3','$ques4','$ques5','$ques6','$ques7')";
    
    // $result = mysqli_query($db,$sql);
    // if (!$result){  
    //     die('Error: ' . mysqli_connect_error());
    // }

    //    echo $unique_id;
    //    $infos = array('error'=>false);
    //    $u_id = array('uid'=>$unique_id);
}
echo json_encode($infos);

//$sql2 = "SELECT * FROM `form`";
//$query = mysqli_query($db, $sql2);
//$infos = array();
//while($info= mysqli_fetch_array($query)){
//    $infos[] =$info;
//}
//header("Loaction:ques_form.php");
?>



