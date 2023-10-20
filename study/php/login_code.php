<?php
    if(isset($co))
    $code = $_POST['code'];
    echo json_encode($code);
    $appid = "wx3c9cf501751dfeef";
    $secret = "91377c2ede6f1e33a585e14522cde9ab";
    $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$code}&grant_type=authorization_code";
    $url = "https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$AppSecret."&js_code=".$code."&grant_type=authorization_code";
	$str = file_get_contents($url);
	$json = json_decode($str);
	$arr = get_object_vars($json);
	echo $openid = $arr['openid']; //这是openid
	echo '</br>';
	echo $session_key = $arr['session_key']; //这是session_key
?>