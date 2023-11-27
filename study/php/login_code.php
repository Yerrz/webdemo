<?php
header('Content-Type:text/html;charset=utf-8');//设置页面编码格式为UTF-8
    if(isset($_POST['nickname']) && isset($_POST['code'])){
        $nickname = $_POST['nickname'];
        $code = $_POST['code'];
    }
    else{
        $code = "code获取失败";
    }
    // $infos['nickname'] = $nickname;

    $appid = "wx3c9cf501751dfeef";
    $secret = "91377c2ede6f1e33a585e14522cde9ab";
    // 使用code获取openid、session_key
    $url = "https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$secret."&js_code=".$code."&grant_type=authorization_code";
    //获取后台接口调用唯一凭证access_token
    // $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
	$str = file_get_contents($url);
	$json = json_decode($str);
	$arr = get_object_vars($json);
    // echo json_encode($arr);

	// echo $openid = $arr['openid']; //这是openid
	// echo $session_key = $arr['session_key']; //这是session_key

    // 自定义登录态
    $openid = $arr['openid'];
    $session_key = $arr['session_key'];
    $token = md5($openid.$secret.$session_key);
    $infos['token'] = $token;
    // echo "token:".$token;
    // echo "<br>";


    $infos['openid'] = $openid;
    $infos['session_key'] = $session_key;
    echo json_encode($infos);
?>