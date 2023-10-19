<?php
// $appid = "wx3c9cf501751dfeef";
// $secret = "91377c2ede6f1e33a585e14522cde9ab";
// $url = 'https://api.weixin.qq.com/cgi-bin/jscode2session?appid=wx3c9cf501751dfeef&secret=91377c2ede6f1e33a585e14522cde9ab';

// //yourAppid为开发者appid.appSecret为开发者的appsecret,都可以从微信公众平台获取；

// $info = file_get_contents($url);//发送HTTPs请求并获取返回的数据，推荐使用curl

// $json = json_decode($info);//对json数据解码

// $arr = get_object_vars($json);

// $openid = $arr['openid'];

// $session_key = $arr['session_key'];

$appid = "wx3c9cf501751dfeef";
$secret = "91377c2ede6f1e33a585e14522cde9ab";
function getAccessToken(){
    global $appid,$secret;
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    $output = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($output,true);
    return $json["access_token"];
}
getAccessToken();

// function getAccessToken(){
//     $appId='wx3c9cf501751dfeef';
//     $secret='91377c2ede6f1e33a585e14522cde9ab';
//     $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appId."&secret=".$secret;
//     $data = doGetCurl($url);
//     return cache('access_token',$data->access_token,$data->expires_in);

// }
// getAccessToken();
?>