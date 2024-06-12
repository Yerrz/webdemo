<?php
function get_api($url){
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
$appid = "wx3c9cf501751dfeef";
$secret = "91377c2ede6f1e33a585e14522cde9ab";
// 获取access_token
$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
$res = get_api($url);
$res = json_decode($res,true);
$access_token = $res['access_token'];
// echo $access_token;
echo json_encode(get_api($url));
// var_dump(get_api($url));

// //URL Scheme通过调用如下接口
// $url2 = "https://api.weixin.qq.com/wxa/generatescheme?access_token=".$access_token;
// $res2 = get_api($url2);
// $res2 = json_decode($res2,true);
// $openlink = $res2['openlink'];
// echo $openlink;
// var_dump(get_api($url2));

//curl POST请求
//URL Scheme通过调用如下接口
$url2 = "https://api.weixin.qq.com/wxa/generatescheme?access_token=".$access_token; // 设置目标API地址
// $data = array('key' => 'value','key2' => 'value2'); // POST数据
$data = array(
      "jump_wxa"=>array(
        "path"=>"/pages/testapi/testapi",
        "query"=>""
      ),
      "is_expire"=>true,
      "expire_time"=>1606737600
    ); // POST数据
// 初始化cURL会话
$ch = curl_init();
// 配置cURL选项
curl_setopt($ch, CURLOPT_URL, $url2);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
// 执行cURL请求并获取结果
$result = curl_exec($ch);
// 关闭cURL会话
curl_close($ch);
// 处理返回结果
echo $result;

// function hmac_sha256($key, $data) {
//     return hash_hmac('sha256', $data, $key, true);
// }
// echo hmac_sha256('F0bh0UGDjw7uOsZULPqflg==','');

// 签名校验
// 小程序可以直接通过各种前端接口获取微信提供的开放数据。但如果开发者服务端也需要获取这些开放数据，
// 微信会对这些开放数据做签名和加密处理。开发者后台拿到开放数据后可以对数据进行校验签名和解密，来保证数据不被篡改。
// 1、通过调用接口（如 wx.getUserInfo）获取数据时，接口会同时返回 rawData、signature，其中 signature = sha1( rawData + session_key )
// 2、 开发者将 signature、rawData 发送到开发者服务器进行校验。服务器利用用户对应的 session_key 使用相同的算法计算出签名 signature2 ，比对 signature 与 signature2 即可校验数据的完整性。
?>