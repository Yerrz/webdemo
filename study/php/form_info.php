<?php

require_once "conn.php";



if(!empty($_POST['ques1']) && !empty($_POST['ques2']) && !empty($_POST['ques3']) && !empty($_POST['ques4']) && !empty($_POST['ques5']) && !empty($_POST['ques6']) && !empty($_POST['ques7'])) {
    $sql = "INSERT INTO `form`(`ques1`, `ques2`, `ques3`, `ques4`, `ques5`, `ques6`, `ques7`) VALUES('$_POST[ques1]','$_POST[ques2]','$_POST[ques3]','$_POST[ques4]','$_POST[ques5]','$_POST[ques6]','$_POST[ques7]')";
    
    $result = mysqli_query($db,$sql);
    if (!$result){  
        die('Error: ' . mysqli_connect_error());
    }
}

$uid = $_GET['id'];
//echo $uid;
$sql = "select * from `form` where `id`=$uid";
$query = mysqli_query($db, $sql);
$info = $info= mysqli_fetch_array($query);

?>

<html>
    <head>
        <title>问卷信息</title>
    </head>
    <style>
        h1 {
            text-align: center;  
        }
        .div2 {
            /*border:1px solid red;*/
            width:80%;
            margin:0 auto;
        }
        .div2 a {
            font-size: 20px;
            line-height: 60px;
        }
        .input {
            display:block;
            margin-left: 35px;
            width:50%;
            height:30px;
            font-size: 16px;
        }
        label {
            display: block;
            margin-left: 32px;
        }
        textarea {
            display: block;
            margin-left: 32px;
            width:50%;
            height:200px;
        }
        .button {
            border: 1px solid gray;
            border-radius: 5px;
            width:70px;
            height:35px;
            background: gainsboro;
            /*margin-left:200px;*/
            margin: 50px 200px;
        }
    </style>
    <body>
        <h1>用户：<?php echo $uid; ?></h1>
        <div class="div2">
            <div class="ques_div01">
                <a>1、你的生活费用是多少元？</a>
                <input class="input" name="ques1" value="<?php echo $info['ques1'];?>" disabled />
            </div>
            <div class="ques_div02">
                <a>2、你的生活来源是？</a>
                <input class="input" name="ques2" value="<?php echo $info['ques2'];?>" disabled />
            </div>
            <div class="ques_div03">
                <a>3、你的年级是：</a>
                <label><input type="radio" value="大一" <?php if($info['ques3'] == "大一"){ echo 'checked';}else{ echo 'disabled';} ?> />大一</label>
                <label><input type="radio" value="大二" <?php if($info['ques3'] == "大二"){ echo 'checked';}else{ echo 'disabled';} ?> />大二</label>
                <label><input type="radio" value="大三" <?php if($info['ques3'] == "大三"){ echo 'checked';}else{ echo 'disabled';} ?> />大三</label>
                <label><input type="radio" value="大四" <?php if($info['ques3'] == "大四"){ echo 'checked';}else{ echo 'disabled';} ?> />大四</label>
            </div>
            <div class="ques_div04">
                <a>4、你通常消费在那些方面？</a>
                <?php
                    $use_lis = array("购物","饮食","娱乐","旅游","交通","通讯","礼物","其他");
                    $inf_ques = explode(",",$info['ques4']);
//                    var_dump(explode(",", $inf_ques));

                    for($i=0;$i<count($use_lis);$i++){
                        if(in_array($use_lis[$i],$inf_ques,true)){
                            echo "<label><input type='checkbox' value=".$use_lis[$i]." checked />".$use_lis[$i]."</label>";
                        }
                        else{
                            echo "<label><input type='checkbox' value=".$use_lis[$i]." disabled />".$use_lis[$i]."</label>";
                        } 
                    }
//                    var_dump($use_lis);

                ?>
<!--            <label><input type="checkbox" value="购物" <?php if($info['ques4'] == "购物"){ echo 'checked';}else{ echo 'disabled';} ?> />购物</label>
                <label><input type="checkbox" value="饮食" <?php if($info['ques4'] == "饮食"){ echo 'checked';}else{ echo 'disabled';} ?> />饮食</label>
                <label><input type="checkbox" value="娱乐" <?php if($info['ques4'] == "娱乐"){ echo 'checked';}else{ echo 'disabled';} ?> />娱乐</label>
                <label><input type="checkbox" value="旅游" <?php if($info['ques4'] == "旅游"){ echo 'checked';}else{ echo 'disabled';} ?> />旅游</label>
                <label><input type="checkbox" value="交通" <?php if($info['ques4'] == "交通"){ echo 'checked';}else{ echo 'disabled';} ?> />交通</label>
                <label><input type="checkbox" value="通讯" <?php if($info['ques4'] == "通讯"){ echo 'checked';}else{ echo 'disabled';} ?> />通讯</label>
                <label><input type="checkbox" value="礼物" <?php if($info['ques4'] == "礼物"){ echo 'checked';}else{ echo 'disabled';} ?> />礼物</label>
                <label><input type="checkbox" value="其他" <?php if($info['ques4'] == "其他"){ echo 'checked';}else{ echo 'disabled';} ?> />其他</label>-->
            </div>
            <div class="ques_div05">
                <a>5、你的性别是：</a>
                <label><input type="radio" value="男" <?php if($info['ques5'] == "男"){ echo 'checked';}else{ echo 'disabled';} ?> />男</label>
                <label><input type="radio" value="女" <?php if($info['ques5'] == "女"){ echo 'checked';}else{ echo 'disabled';} ?> />女</label>
            </div>
            <div class="ques_div06">
                <a>6、你觉得自己会因为恋爱消费而导致生活紧张吗？</a>
                <label><input type="radio" value="不会有影响" <?php if($info['ques6'] == "不会有影响"){ echo 'checked';}else{ echo 'disabled';} ?> />不会有影响</label>
                <label><input type="radio" value="会有很大影响" <?php if($info['ques6'] == "会有很大影响"){ echo 'checked';}else{ echo 'disabled';} ?> />会有很大影响</label>
                <label><input type="radio" value="多少会有点影响" <?php if($info['ques6'] == "多少会有点影响"){ echo 'checked';}else{ echo 'disabled';} ?> />多少会有点影响</label>
            </div>
            <div class="ques_div07">
                <a>7、你对我们的建议是：</a>
                <textarea class="textarea" name="ques7"  rows="5" cols="40" disabled><?php echo $info['ques7']; ?></textarea>
            </div>
            <button class="button" onclick="javascript:window.location.href='ques_form.php'">返回</button>
        </div>
    </body>
</html>
