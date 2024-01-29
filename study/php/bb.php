<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <script src="../js/jquery/jquery-1.8.0.js"></script>
        <script type="text/javascript">
            // 从sessionStorage读取数据
            const username = sessionStorage.getItem('username'); // "John"
            const sessionStorageTest = sessionStorage.getItem('sessionStorageTest'); // "true"
            console.log(username);
            $(function(){
                $('#back').click(function(){
                    // window.location.href = "aa.php";
                    window.history.go(-1);
                });
            })
        </script>
        <style>
            #main {border:1px solid blanchedalmond;}
            #div01 {border:1px solid red;width: 800px;margin: 0 auto;}
            p{overflow-wrap: break-word;word-wrap: break-word;}
            strong{float: left;}
        </style>
    </head>
    <body>
        <button id="back">返回</button>
        <div id="main">
            <div id="div01">
                <p><strong>检索结果：</strong>UA101017152721899,UA110418203620703,UA130328143651762,UA140307180853751,UA150703105634727,UA150929165520682,UA180404100530981,UA190130141043427,UA170602160053851,UA121012115224448,UA180326182411516UA101017152721899,UA110418203620703,UA130328143651762,UA140307180853751,UA150703105634727,UA150929165520682,UA180404100530981,UA190130141043427,UA170602160053851,UA121012115224448,UA180326182411516</p>
            </div>
        </div>
    </body>
</html>

<?php 
header('Content-Type:text/html;charset=utf-8');//设置页面编码格式为UTF-8
echo md5('Ni好'); 
echo '<br>';

echo openssl_encrypt('Ni好','AES-128-ECB','mykey');
echo '<br>';
$data = base64_decode('QPI07s6biSHd/s6Umwd0Iw==');
echo openssl_decrypt($data,'AES-128-ECB','mykey',OPENSSL_RAW_DATA);
echo '<br>';

// 判断一个字符串是否为回文
function isPalindrome($str) {
    $len = strlen($str);
    for ($i = 0; $i < $len / 2; $i++) {
        if ($str[$i]!= $str[$len - $i - 1]) {
            return false;
        }
    }
    return true;
}
$str = 'ababa';
if(isPalindrome($str)) echo 'yes';
else echo 'no';
echo '<br>';

// 对字符串进行对称加密
function encrypt($str) {
    $key ='mykey';
    $str = base64_encode($str);
    $str = openssl_encrypt($str, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
    return $str;
}
$endata = encrypt('哈哈测试');
// 对字符串进行对称解密
function decrypt($str) {
    $key ='mykey';
    $str = openssl_decrypt($str, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
    $str = base64_decode($str);
    return $str;
}
echo decrypt($endata);

echo '<br>';
$userInfo = array(0=>array('sName'=>'aa','sEmail'=>123,'type'=>'申请'),1=>array('sName'=>'bb','sEmail'=>1234565,'type'=>'审核'));
foreach ($userInfo as $key => $value) {
    echo $value['sName'];
    echo '<br>';
}
print_r($userInfo);
?>