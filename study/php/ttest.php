<?php
header('Content-Type:text/html;charset=utf-8');//设置页面编码格式为UTF-8
    $data1 = stripslashes("\data");   //去除\
    $data2 = "<script>alert('hack')</script>";   //防止xss漏洞，完全显示
    echo $data1;
    echo htmlspecialchars($data2);

    echo "<br>";
    // 判断是否是数字,去除所有空格
    $num = "2  3234         3   ，必好n.s  \t";
    echo preg_replace("# #","",$num);
    if(is_numeric($num)){
        echo "--yes";
    }
    else{
        echo "--no";
    }
    echo "<br>";

    // 判断输入是否全是汉字
    $input = "我爱课";
    $pattern = "/^[\x{4e00}-\x{9fa5}]+$/u";
    if(preg_match($pattern,$input)){
        echo "ook";
    }
    else{
        echo "nno";
    }
    echo $input+"<br>";

    // 判断数据是否是汉字 
    $str="输出是"; 
    // 只匹配汉字
    // /^[\x{4e00}-\x{9fa5}]+$/u
    // 只匹配汉字和汉字符号
    // /^[\x7f-\xff]+$/
    if(preg_match("/^[\x80-\xff]+$/","$str")){ 
        echo "全是中文"; 
    }else{ 
        echo "不是"; 
    } 

    // $url = "https://api.weixin.qq.com/wxa/business/getuserencryptkey?access_token=ACCESS_TOKEN&openid=OPENID&signature=SIGNATURE&sig_method=hmac_sha256";
    // echo json_decode($key);

// 初始化一个cURL对象
$curl = curl_init();
// 设置您需要抓取的URL
curl_setopt($curl, CURLOPT_URL, 'http://www.example.com');
// 设置header
curl_setopt($curl, CURLOPT_HEADER, 1);
// 设置cURL参数，要求结果保存到字符串中还是输出到屏幕上
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
// 运行cURL，请求网页
$data = curl_exec($curl);
// 关闭URL请求
curl_close($curl);
// 显示获得的数据
var_dump($data);



?>