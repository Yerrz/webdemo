<!-- 结合版权在线的分类标签滚动菜单 -->
<?php

/**
 * 手机版 - 作品信息列表 - 锐拓版权在线
 *
 */
require_once 'lib/conf_booktags.php';
$d_conn = new DB();

$t1 = (int)$_GET['t1'];
$t2 = (int)$_GET['t2'];
$t3 = (int)$_GET['t3'];
$l = $_GET['l']; //语种

if (!empty($l)) $lng = $_BOOKPUBLNG[$li];

/**
 * 访问记录保存 作品列表
 * 在本页面保存 可以防止翻页多次刷新被记录
 */
$sPubLng = $lng;
$sId = $_SESSION['RCI']['USER']['UID']; //访问用户
$belongUsrId = $_SESSION['RCI']['USER']['AGENT']['UID']; //版权顾问
if (!empty($sPubLng) || !empty($t1) || !empty($t2) || !empty($t3)) { //原版语种或分类都为空就不记录
    $sUserAgent = $_SERVER['HTTP_USER_AGENT'];
    $aBrowser = GetClientBrowser();
    $sBrowser = $aBrowser['browser'] . ' ' . $aBrowser['version'];
    $sOS = GetClientOS();
    $sIP = GetClientIP();
    if (UserAgentAllowRecord() == TRUE) { //判断当前访问请求是否需要记录日志
        //获取联络人、企业
        $sIndvType = $sCorpType = $sBelongIndvId = $sBelongCorpId = '';
        if (isset($_SESSION['RCI']['USER']['TYPE'])) $sIndvType = $sCorpType = $_SESSION['RCI']['USER']['TYPE'];
        if (isset($_SESSION['RCI']['USER']['INDV'])) $sBelongIndvId = $_SESSION['RCI']['USER']['INDV'];
        if (isset($_SESSION['RCI']['USER']['CORPID'])) $sBelongCorpId = $_SESSION['RCI']['USER']['CORPID'];

        $d_conn->query("INSERT INTO roa_pdm_book_log_view (sId, sPubLng, belongUsrId, idBookTag_1, idBookTag_2, idBookTag_3, sIP, sUserAgent, sBrowser, sOS, sBelongIndvId, sBelongCorpId, sIndvType, sCorpType, sRemarks) "
            . "VALUES('{$sId}','{$sPubLng}','{$belongUsrId}',$t1,$t2,$t3, '{$sIP}', '{$sUserAgent}', '{$sBrowser}', '{$sOS}','{$sBelongIndvId}','{$sBelongCorpId}','{$sIndvType}','{$sCorpType}', 'mobile')");
    }
}

// 图书分类标签展示
function showTopic($arr, $tn, $keys)
{
    global $_BOOKTAGS;
    // 获取分类级别
    $t = 't' . $tn;
    $t2 = 't' . ($tn + 1);
    // 设置背景颜色
    $topic_colors = array('#FBE6EF', '#FFE9C9', '#D8EEFB');
    $classif_item_colors = array('#FDF7FA', '#FFF7ED', '#F0F8FD');
    $topic_index = 0;
    if ($t == 't2') $topic_index = array_search($keys, array_keys($_BOOKTAGS));
    // 最高级标签
    foreach ($arr as $key => $val) {
        if ($val[0]) {
            // if ($t == 't2') $key = $keys . 'b' . $key;
            if ($t == 't2') $par = 't1=' . $keys . '&t2=' . $key;
            else $par = 't1=' . $key;
            $color = $topic_colors[$topic_index % count($topic_colors)];
            $classif_item_color = $classif_item_colors[$topic_index % count($classif_item_colors)];
            if ($t != 't2') $topic_index++;
            echo '<li style="background-color:' . $classif_item_color . ';">
                  <div class="topic" style="background-color:' . $color . ';" id="topic_' . $key . '" onclick="window.location.href=\'?' . $par . '\'"><a>' . $val[0] . '</a><span>></span></div>
                  <div class="classification">';
            // 次级标签
            foreach ($val[1] as $key2 => $val2) {
                if ($t == 't1'){
                    $value = $val2[0];
                    $par2 = 't1=' . $key . '&t2=' . $key2;
                } 
                elseif ($t == 't2'){
                    $value = $val2;
                    $par2 = 't1=' . $keys . '&t2=' . $key . '&t3=' . $key2;
                } 
                echo '<div class="classif_item" id="classif_item_' . $key . $key2 . '" onclick="window.location.href=\'?' . $par2 . '\'"><label for="classif_item_' . $key . $key2 . '">' . $value . '</label></div>';
            }
            echo '</div></li>';
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,viewport-fit=cover" />
    <title>锐拓版权在线</title>
    <link rel="stylesheet" href="/static/weui/style/weui.min.css" />
    <link rel="stylesheet" href="/script/jflex-master/css/jflex.min.css" type="text/css" media="all">
    <link rel="stylesheet" href="/copyrights/m/css/default.css" />
    <link rel="stylesheet" href="/copyrights/m/css/book-list.css" />
    <script type="text/javascript" src="/script/jquery.1.7.2.min.js"></script>
    <script type="text/javascript" src="/copyrights/js/main.js"></script>
    <script type="text/javascript" src="/static/weui/weui.min.js"></script>
</head>

<body>
    <div id="head">
        <div class="bar">
            <div class="search-main">
                <table>
                    <tr>
                        <td>
                            <form action="/copyrights/list" method="get">
                                <?php
                                $q = $d_conn->conform(trim($_GET['q']));
                                ?>
                                <input type="search" name="q" id="q" size="50" results="10" value="<?php echo $q; ?>" placeholder="请输入需检索的书名或关键词">
                            </form>
                        </td>
                        <td width="1"></td>
                        <td width="40" class="list-switch" id="list-switch"><img src="/copyrights/m/img/list-switch.png"></td>
                        <td>
                            <div class="history-text">
                                <?php
                                if (isset($_GET['history'])) {
                                    echo '<a href="/copyrights/list" class="topToggleButton">列表</a>';
                                } else {
                                    echo '<a href="/copyrights/list?history=1" class="topToggleButton">足迹</a>';
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="clear-both"></div>
            <div class="flex">
                <ul class="slides">
                    <li data-title="1">
                        <a href="javascript:void(0);" onclick="queryDetail('P230318151709357') "><img alt="" src="/copyrights/img/mobile/oRsGl1.jpg"></a>
                    </li>
                    <li data-title="2">
                        <a href="javascript:void(0);" onclick="queryDetail('P230921100141183') "><img alt="" src="/copyrights/img/mobile/1VKSqv.jpg"></a>
                    </li>
                    <li data-title="3">
                        <a href="javascript:void(0);" onclick="queryDetail('P230822103822992') "><img alt="" src="/copyrights/img/mobile/PvKn0V.jpg"></a>
                    </li>
                </ul>
            </div>
        </div>
        <?php
        if (!isset($_GET['history'])) {
        ?>
            <div id="idBookTag">
                <div id="wrapper">
                    <ul id="scroller">
                        <?php
                        if($_GET['t3'] != 0) {
                            // echo 't3';
                            $t1 = $_GET['t1'];
                            $arr_item = $_BOOKTAGS[$t1][1];
                            showTopic($arr_item, 2, $t1);
                        }
                        // 二级分类
                        elseif ($_GET['t2'] != 0) {
                            // echo 't2';
                            $t1 = $_GET['t1'];
                            $arr_item = $_BOOKTAGS[$t1][1];
                            showTopic($arr_item, 2, $t1);
                        }
                        // 一级分类
                        elseif ($_GET['t1'] != 0) {
                            // echo 't1';
                            $t1 = $_GET['t1'];
                            showTopic($_BOOKTAGS, 1, 0);
                        }
                        // 列表
                        else {
                            showTopic($_BOOKTAGS, 1, 0);
                        }
                        ?>
                    </ul>
                </div>
                <div class="clear-both"></div>
            </div>
            <div class="Space"></div>
        <?php
        }
        ?>
    </div>
    <div id="body">
        <ul class="book-list" id='book-list'></ul>
    </div>
    <div id="bottom-menu">
        <ul>
            <li><a href="/copyrights/list"><img src="/copyrights/m/img/menu_home2.png" /></a></li>
            <li><a href="/copyrights/my/favorites"><img src="/copyrights/m/img/menu_myfavorites1.png" /></a></li>
            <li><a href="/copyrights/my/books"><img src="/copyrights/m/img/menu_mybooks1.png" /></a></li>
            <li><a href="/copyrights/my/booktags"><img src="/copyrights/m/img/menu_mybooktags1.png" /></a></li>
            <li><a href="/copyrights/my/profile"><img src="/copyrights/m/img/menu_myprofile1.png" /></a></li>
        </ul>
        <div class="clear-both"></div>
    </div>
    <div class="bottom-top">
        <a id="goback" href="javascript:history.go(-1);"><img src="/copyrights/img/goback.png" alt="返回"></a>
        <a id="to-top" href="#"><img src="/copyrights/img/top-icon2.png" alt="返回顶部"></a>
    </div>
</body>
<script type="text/javascript">
    var booktags = <?php echo json_encode($_BOOKTAGS); ?>;

    $(function() {
        $("#to-top").toTop();

        <?php
        if (isset($_GET['history'])) {
            echo 'getBookListHistory();';
        } else {
        ?>
            //从url获取滚动条高度
            var pageCurrent = getQueryString("pageCurrent"); //获取当前加载页数
            //判断是否为空
            if (pageCurrent == null || pageCurrent == "") {
                getBookList(1);
                document.cookie = "pageCurrent=1";
            } else {
                queryRecord(pageCurrent);
            }

            // 选定图书分类标签时定位
            var patharg = window.location.search;
            if(patharg){
                var paramValue_t1 = getParameterByName('t1');
                var paramValue_t2 = getParameterByName('t2');
                var paramValue_t3 = getParameterByName('t3')
                if (patharg.includes("t3") && paramValue_t3 != 0) {
                    var paramValue = paramValue_t2;
                } else if (patharg.includes("t2") && paramValue_t2 != 0) {
                    var paramValue = paramValue_t2;
                } else if (patharg.includes("t1") && paramValue_t1 != 0) {
                    var paramValue = paramValue_t1;
                }
                var item_left = $('#topic_' + paramValue).offset().left;
                $('#scroller').scrollLeft(item_left - 5);
            }
        <?php } ?>

        // 判断视图显示类型
        var switch_status = sessionStorage.getItem("switch_status");
        if(switch_status){
            $('#book-list').removeClass('book-list').addClass('book-list2');
            $('body').css('background-color','#f7f7f7');
        }
        // 切换视图
        $('#list-switch').click(function(){
            console.log('切换视图');
            var list_class = '';
            if($('#book-list').hasClass('book-list')){
                $('#book-list').removeClass('book-list').addClass('book-list2');
                list_class = 'book-list2';
                $('body').css('background-color','#f7f7f7');
            }else{
                $('#book-list').removeClass('book-list2').addClass('book-list');
                $('body').css('background-color','#fff');
            }
            sessionStorage.setItem("switch_status",list_class);
        });
    });
    // 获取URL查询字符串参数
    function getParameterByName(name) {
        var urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    //获取url参数方法
    function getQueryString(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]);
        return null;
    }
    //如果滚动条高度存在，走该方法。
    function queryRecord(pageCurrent) {
        //从sessionStorage取得缓存数据
        var value = sessionStorage.getItem("datalistHtml");

        if (value) {
            //放入列表中
            $("#book-list").append(value);
            $("#q").val(sessionStorage.getItem("q"));
            //拿到滚动条高度
            var scrollPos = getQueryString("scrollPos");
            //清空缓存数据
            //sessionStorage.clear();
            //设置高度
            window.scrollTo(0, scrollPos);
        } else {
            getBookList(1);
        }
    }
    //查询列表
    function getBookList(page) {
        $('.book-list-bottom').text('加载中..');
        $.ajax({
            type: "GET",
            url: "m/req/req_get_data.php?do=getBookList&q=<?php echo $q; ?>&t1=<?php echo $t1; ?>&t2=<?php echo $t2; ?>&t3=<?php echo $t3; ?>&l=<?php echo $l; ?>&page=" + page,
            success: function(data) {
                $('.book-list-bottom').remove();
                $('#book-list').append(data);
                document.cookie = "pageCurrent=" + page;
            }
        });
    }

    function getCookie(name) {
        // 拆分 cookie 字符串
        var cookieArr = document.cookie.split(";");

        // 循环遍历数组元素
        for (var i = 0; i < cookieArr.length; i++) {
            var cookiePair = cookieArr[i].split("=");

            /* 删除 cookie 名称开头的空白并将其与给定字符串进行比较 */
            if (name == cookiePair[0].trim()) {
                // 解码cookie值并返回
                return decodeURIComponent(cookiePair[1]);
            }
        }
        // 如果未找到，则返回null
        return null;
    }

    function getBookListHistory() {
        $.ajax({
            type: "GET",
            url: "m/req/req_get_data.php?do=getBookListHistory",
            data: "",
            success: function(data) {
                $('#book-list').html(data);
            }
        });
    }
    //查看详情
    function queryDetail(sid) {
        var source = "book-list";
        <?php
        if (isset($_GET['history'])) {
            echo 'source="book-list-history";';
        }
        ?>
        var scrollPos = getScrollTop();
        //存入sessionStorage中
        var datalistHtml = $("#book-list").html();
        sessionStorage.setItem("datalistHtml", datalistHtml);
        sessionStorage.setItem("q", '<?php echo $q; ?>');
        window.location.href = "/copyrights/book?id=" + sid + "&source=" + source + "&pageCurrent=" + getCookie('pageCurrent') + "&scrollPos=" + scrollPos + "&t1=<?php echo $t1; ?>&t2=<?php echo $t2; ?>&t3=<?php echo $t3; ?>&l=<?php echo $l; ?>";
    }
    /** 
     * 获取滚动条距离顶端的距离 
     * @return {}支持IE6 
     */
    function getScrollTop() {
        var scrollPos;
        if (window.pageYOffset) {
            scrollPos = window.pageYOffset;
        } else if (document.compatMode && document.compatMode != 'BackCompat') {
            scrollPos = document.documentElement.scrollTop;
        } else if (document.body) {
            scrollPos = document.body.scrollTop;
        }
        return scrollPos;
    }
</script>
<script type="text/javascript" src="/script/jflex-master/js/jflex.min.js"></script>
<script type="text/javascript">
    $('.flex').jFlex({
        autoplay: true,
        showArrows: true,
        swipeable: true,
        titles: "bottom",
        hasTitles: false,
        timing: 3000
    });
</script>

</html>