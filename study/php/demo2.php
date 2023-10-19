<?php
$appid = "wx3c9cf501751dfeef";
$secret = "91377c2ede6f1e33a585e14522cde9ab";
//1、获取access_token
function getAccessToken(){
    global $appid,$secret;
    $unl = "https://api.weixin.q.com/cgi-bin/tokengrant_type=client_credential&appid=".$appid."&secret=".$secret;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    $output = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($output,true);
    return $json[ "access_token"];
}
getAccessToken();

