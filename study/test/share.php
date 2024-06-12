<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <script src="../js/jquery/jquery-1.8.0.js"></script>
</head>
<style>
    #main{
        display: flex;
    }
    #main>div{
        background-color: red;
        margin-right: 10px;
    }
</style>

<body>
    <button id="back">返回</button>
    <div id="detail">我正在分享一个...</div>
    <div id="main">
        <div id="qq" onclick="shareTo('qq')">QQ</div>
        <div id="qzone" onclick="shareTo('qzone')">QQ空间</div>
        <div id="sina" onclick="shareTo('sina')">新浪微博</div>
        <div id="wechat" onclick="shareTo('wechat')">微信</div>
        <a href="mqq://">打开QQ</a>
    </div>
</body>

</html>

<?php
echo md5("你好aa");
?>

<script>
    function shareTo(types) {

        var title, imageUrl, url, description, keywords;

        //获取文章标题
        title = document.title;

        //获取网页中内容的第一张图片地址作为分享图
        //imageUrl = document.images[0].src;
        imageUrl = 'http://shop.nyistqiusuo.cn//index/images/icons/logo-orange.png';
        //当内容中没有图片时，设置分享图片为网站logo
        if (typeof imageUrl == 'undefined') {
            imageUrl = 'https://' + window.location.host + '/static/images/logo.png';
        } else {
            imageUrl = imageUrl.src;
        }

        //获取当前网页url
        url = document.location.href;

        //获取网页描述
        description = document.getElementsByClassName('page-book-name').innerHTML;

        //获取网页关键字
        keywords = '测试';

        //qq空间接口的传参
        if (types == 'qzone') {
            window.open('https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=' + encodeURIComponent(url) + '&sharesource=qzone&title=' + title + '&pics=' + imageUrl + '&summary=' + description);
        }
        //新浪微博接口的传参
        if (types == 'sina') {
            window.open('http://service.weibo.com/share/share.php?url=' + encodeURIComponent(url) + '&sharesource=weibo&title=' + title + '&pic=' + imageUrl + '&appkey=2706825840');
        }
        // //qq好友接口的传参
        // if (types == 'qq') {
        //     window.open('http://connect.qq.com/widget/shareqq/index.html?url=' + encodeURIComponent(url) + '&sharesource=qzone&title=' + title + '&pics=' + imageUrl + '&summary=' + description + '&desc=' + keywords);
        // }
        //生成二维码给微信扫描分享
        if (types == 'wechat') {
            //在线二维码（服务器性能限制，仅测试使用，屏蔽非大陆ip访问）
            // window.open('https://zixuephp.net/inc/qrcode_img.php?url=' + url);
        }

    }

    // 分享按钮点击事件
document.getElementById('qq').addEventListener('click', function() {
    // pc端
    // window.location.href = 'https://graph.qq.com/oauth2.0/show?which=Login&display=pc&client_id=101019034&response_type=code&scope=get_info%2Cget_user_info&redirect_uri=http://example.com&image_url=http://example.com/img.png'
  // 唤醒QQ的Custom Scheme
  window.location.href = 'mqqapi://share/to_fri?src_type=app&version=1&file_type=news&title=分享标题&description=分享描述&url=http://example.com&image_url=http://example.com/img.png';
  
  // 设置定时器检测是否打开了QQ
  var startTime = Date.now();
  setTimeout(function() {
    if (Date.now() - startTime < 3000) {
      // 如果在3秒内没有打开QQ，则跳转到QQ下载页面
      window.location.href = 'https://im.qq.com/mobileqq/';
    }
  }, 2000);
});


//复制
$('#qq').click(function() {
    if (navigator.clipboard) {
            navigator.clipboard.writeText(copyText).then(function() {
                console.log('内容已成功复制到剪贴板');
            }, function(err) {
                console.error('无法复制内容到剪贴板：', err);
            });
        } else {
            console.log('当前浏览器不支持Clipboard API');
            // 如果浏览器不支持，您可以尝试使用旧版的execCommand方法（部分老旧浏览器支持）
            var textarea = document.createElement('textarea');
            textarea.value = copyText;
            document.body.appendChild(textarea);
            textarea.select();
            try {
                var successful = document.execCommand('copy');
                if (successful) {
                    console.log('内容已成功复制到剪贴板');
                } else {
                    console.log('复制失败，请更新您的浏览器以获得更好的体验');
                }
            } catch (err) {
                console.log('复制失败：', err);
            }
            document.body.removeChild(textarea);
        }
});
</script>