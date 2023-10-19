<?php
    $appid = "wx3c9cf501751dfeef";
    $secret = "91377c2ede6f1e33a585e14522cde9ab";
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    $output = curl_exec($ch);
    curl_close($ch);
    var_dump($output);
?>