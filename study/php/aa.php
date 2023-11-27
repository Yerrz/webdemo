<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <script src="../js/jquery/jquery-1.8.0.js"></script>
    <script type="text/javascript">
        $(function() {
            $("button").click(function() {
                $("#p1").css("color", "red").slideUp(2000).slideDown(2000);
            });

            //替换字符串
            var tex = "Hello world!";
            var tex_new = tex.replace('world','Kitty');
            console.log(tex_new);

            //翻转字符串
            var res = 'I love China lo';
            console.log(res);
            var str = res.split('').reverse().join("");
            $('#test').text(str);

            //使用new Date();
            var time = new Date(2323, 5, 1); //年、月、日（月是0-11）
            console.log(time);

            $("#tabs").tabs();
            $("#tabs2").tabs();

        })
    </script>
    <style>
        .SP ul li {
            list-style: none;
            display: inline-block;
        }

        .SP .BtnSet label {
            border: 1px solid #d8dcdf;
            padding: 5px;
            background-color: #eeeeee;
            font-weight: bold;
            color: #004276;
        }

        .SP .BtnSet input {
            display: none;
        }
    </style>
</head>

<body>
    <div>
        <p id="test">test</p>
    </div>
    <div class="SP" style="border:1px solid red;">
        <ul class="h">
            <li class="text">服务器位置</li>
            <li class="redstar">*</li>
            <li class="input">
                <div class="BtnSet">
                    <input type="radio" name="sArchiveLocation_0" id="sArchiveLocation_0" value="单色" <?php if ($row['sInnerPress'] == '单色') echo 'checked="checked"'; ?> /><label for="sArchiveLocation_0">万和1609</label>
                    <input type="radio" name="sArchiveLocation_1" id="sArchiveLocation_1" value="双色" <?php if ($row['sInnerPress'] == '双色') echo 'checked="checked"'; ?> /><label for="sArchiveLocation_1">光华1408</label>
                </div>
            </li>
        </ul>
    </div>

    <div><input type="radio" value="" 2>2</div>
    <p id="p1">jQuery 乐趣十足！</p>
    <button>点击这里</button>
    <hr>

    <div id="tabs">
        <ul>
            <li><a href="#tab_sys_info">系统信息</a></li>
        </ul>
        <div id="tab_sys_info" style="min-height: 500px;">
            <div class="wide-info">
                <ul class="field">
                    <li class="title">更改记录</li>
                    <li class="data" id="chglog"><button id="btn_chglog_load" class="btn_2 CN">显示</button></li>
                </ul>
            </div>
        </div>
    </div>
    <div id="tabs2">
        <ul>
            <li><a href="#tab_sys_info2">系统信息2</a></li>
        </ul>
        <div id="tab_sys_info2" style="min-height: 500px;">
            <div class="wide-info">
                <ul class="field">
                    <li class="title">更改记录2</li>
                    <li class="data" id="chglog"><button id="btn_chglog_load" class="btn_2 CN">显示</button></li>
                </ul>
            </div>
        </div>
    </div>


</body>

</html>

<?php
echo "<br>" . "<br>" . "<br>" . "这是php的测试内容" . "<br>";
include('conn.php');
// $sql = "select * from form where id=75";
// $query = mysqli_query($db,$sql);
// if(!$query){
//     die('error'.mysqli_error($db));
// }
// while ($row = mysqli_fetch_assoc($query)) {
//     $PlanDateArray[] = $row;
// }
// print_r($PlanDateArray);

$sql = "select * from test where start<=NOW()";
$query = mysqli_query($db, $sql);
if (!$query) {
    die('error' . mysqli_error($db));
}
while ($row = mysqli_fetch_assoc($query)) {
    $PlanDateArray[] = $row;
}
print_r($PlanDateArray);

echo '<br>';
echo date('Y-m-d H:i:s');

// if('00:00' < '09:00'){
//     echo '09:00';
// }

echo '<br>';
echo strlen('abcdefg');

echo implode('', explode('-', '2023-11-08'));

$lis = array();
array_push($lis,'aa','bb','cc','dd','ee',23,43,'hello','ac','bbc');
print_r($lis);
echo '数组lis长度：'.sizeof($lis);
var_dump(in_array('ac',$lis));   //返回boolean true

echo substr('I love China',3,5); //ove C  截取
echo strlen('I love China');  //12        计算长度
echo strpos('I love China lo','lo');  //2     查找位置
echo strstr('I love China lo','lo');  //love China lo   截取所给字符至最后
echo strrev('I love China lo');  //ol anihC evol I   翻转

echo "<br>";

echo str_replace("world", "Kitty", "Hello world!");   //Hello Kitty! 替换字符串
echo str_word_count("Kitty Hello world ha.cn!");   //5  统计字符串中单词数

//print_r   var_dump
echo "<br>";
$lis = array("Peter"=>"Le","Marcy"=>"Smith","Licy"=>"Hilly","Gary"=>"23",35=>"Nice Day");
print("<br>This is 'print_r':");
print_r($lis);
print("<br>This is 'var_dump':");
var_dump($lis);


?>