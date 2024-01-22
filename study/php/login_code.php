<?php
header('Content-Type:text/html;charset=utf-8');//设置页面编码格式为UTF-8
    $msg = 200;
    if(isset($_POST['code'])){
        // 1、获取前端传来的code、encryptedData、iv、signature、rawData
        $code = $_POST['code'];
        $encryptedData = $_POST['encryptedData'];
        $iv = $_POST['iv'];
        $signature = $_POST['signature'];
        $rawData = $_POST['rawData'];
    }
    else{
        $msg = "ERROR";
    }

    $appid = "wx3c9cf501751dfeef";
    $secret = "91377c2ede6f1e33a585e14522cde9ab";
    // 2、使用code获取openid、session_key
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

    ///3、使用session_key进行签名校验，校验数据的完整性
    $signature2 = sha1( $rawData.$session_key );
    if( $signature != $signature2 ) $msg = "ERROR";
    else{
        // 4、签名校验成功，解密encryptedData
        include_once "wxBizDataCrypt.php";
        $pc = new WXBizDataCrypt($appid, $session_key);
        $errCode = $pc->decryptData($encryptedData, $iv, $data );

        if ($errCode == 0) {
            // 5、返回解密数据和自定义登录态
            $dataobj = json_decode($data,true);
            $token = md5($openid.$session_key);
            $dataobj['token'] = $token;

            include("conn.php");
            $sql = "INSERT INTO login_auth(sOpenId,sSesssionkey,sToken,nickName,avatarUrl,gender,language) VALUES('{$openid}','{$session_key}','{$token}','{$nickName}','','{$gender}','{$language}')";
            // mysqli_query($db,$sql);


            $dataobj['openid'] = $openid;
            // $data = json_encode($dataobj);
            // print($data . "\n");
        } else {
            $msg = "ERROR";
            // print($errCode . "\n");
        }
    }
    $res['msg'] = $msg;
    $res['data'] = $dataobj;
    echo json_encode($res);

    // include("conn.php");
    // $sql_login = "SELECT * FROM login_auth WHERE sOpenId='{$openid}'";
    // $query = mysqli_query($db, $sql_login);
    // if(mysqli_num_rows($query) != 0){
    //     $token = md5($openid.$session_key);
    //     $sql = "INSERT INTO login_auth(sOpenId,sSesssionkey,sToken) VALUES('{$openid}','{$session_key}','{$token}')";
    //     mysqli_query($db,$sql);
    // }
    // mysqli_free_result($query);mysqli_close($db);

    // PHP加密方式分为单项散列加密，对称加密，非对称加密
    // 单项散列加密：不可逆，例如：MD5、hash、crypt、sha1
    // 对称加密：可逆，加密解密用的同一秘钥，例如：URL编码、base64编码
    // 非对称加密：可逆，加密解密用的不同秘钥
    // MD5是一种安全的散列算法和加密散列函数，可以检测一些数据损坏，但主要用于正在传输的数据的安全加密和数字证书的验证(生成固定长度的32位字符的十六进制数，不可逆即无法恢复)
    // $token = openssl_encrypt($openid.$session_key,'AES-128-ECB','mykey');
    // $data = base64_decode($token);
    // echo openssl_decrypt($data,'AES-128-ECB','mykey',OPENSSL_RAW_DATA);
    // $token = md5($openid.$session_key);
    // $infos['token'] = $token;

    // include("conn.php");
    // $sql = "INSERT INTO login_auth(sOpenId,sSesssionkey,sToken,nickName,avatarUrl,gender,language) VALUES('{$openid}','{$session_key}','{$token}','{$nickName}','','{$gender}','{$language}')";
    // // mysqli_query($db,$sql);


    // $infos['openid'] = $openid;
    // $infos['session_key'] = $session_key;
    // echo json_encode($infos);
?>