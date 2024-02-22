<!-- 1、不使用滚动组件，使用overflow-x
2、设置点击标签发送请求后新页面能定位到选中文字位置 -->
<?php
require_once '../copyrights/lib/conf_booktags.php';

// 分类标签展示
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
            if ($t == 't2') $key = $keys . 'b' . $key;
            $color = $topic_colors[$topic_index % count($topic_colors)];
            $classif_item_color = $classif_item_colors[$topic_index % count($classif_item_colors)];
            if ($t != 't2') $topic_index++;
            echo '<li style="background-color:' . $classif_item_color . ';">
                  <div class="topic" style="background-color:' . $color . ';" id="topic_' . $key . '" onclick="window.location.href=\'?do=list&' . $t . '=' . $key . '\'"><a>' . $val[0] . '</a><span>></span></div>
                  <div class="classification">';
            // 次级标签
            foreach ($val[1] as $key2 => $val2) {
                if ($t == 't1') $value = $val2[0];
                elseif ($t == 't2') $value = $val2;
                echo '<div class="classif_item" id="classif_item_' . $key . $key2 . '" onclick="window.location.href=\'?do=list&' . $t2 . '=' . $key . 'b' . $key2 . '\'"><label for="classif_item_' . $key . $key2 . '">' . $value . '</label></div>';
            }
            echo '</div></li>';
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">

    <title>iScroll demo: Event Passthrough</title>

    <!-- <script type="text/javascript" src="iscroll.js"></script> -->
    <script type="text/javascript" src="../oa/script/jq/jquery.min.js"></script>

    <script type="text/javascript">
        var booktags = <?php echo json_encode($_BOOKTAGS); ?>;
        $(function() {
            var patharg = window.location.search;
            var windowWidth = $(window).width();
            console.log("windowWidth:", windowWidth);
            console.log("patharg:", patharg);
            if (patharg.includes("t1")) {
                var paramValue = getParameterByName('t1');
                console.log("t1的paramValue:", paramValue);
                // $('#topic_' + paramValue).closest('li').css('border', '1px solid red');
                var item_left = $('#topic_' + paramValue).offset().left;
                var item_width = $('#topic_' + paramValue).closest('li').width();
                var item_offset = item_left - ((windowWidth/2) - (item_width/2));
                console.log(item_left);
                $('#scroller').scrollLeft(item_offset);
                // $('#scroller').css('transform', 'translate(-390px, 0px) translateZ(0px)');
            } else if (patharg.includes("t2")) {
                console.log("t2");
                var paramValue = getParameterByName('t2');
                // $('#topic_' + paramValue).closest('li').css('border', '1px solid red');
                var item_left = $('#topic_' + paramValue).offset().left;
                var item_width = $('#topic_' + paramValue).closest('li').width();
                var item_offset = item_left - ((windowWidth/2) - (item_width/2));
                // console.log(item_left);
                $('#scroller').scrollLeft(item_offset);
            } else if (patharg.includes("t3")) {
                console.log("t3");
                var paramValue = getParameterByName('t3').split('b');
                paramValue = paramValue[0] + 'b' + paramValue[1];
                // $('#topic_' + paramValue).closest('li').css('border', '1px solid red');
                var item_left = $('#topic_' + paramValue).offset().left;
                var item_width = $('#topic_' + paramValue).closest('li').width();
                var item_offset = item_left - ((windowWidth/2) - (item_width/2));
                $('#scroller').scrollLeft(item_offset);
            }
            // showTopic(booktags);
            // console.log("booktags:", booktags);
        });

        // 获取URL查询字符串参数
        function getParameterByName(name) {
            var urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }
    </script>

    <style type="text/css">
        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        body,
        ul,
        li {
            padding: 0;
            margin: 0;
            border: 0;
        }

        body {
            font-size: 1rem;
            font-family: ubuntu, helvetica, arial;
        }

        #header {
            width: 100%;
            height: 45px;
            line-height: 45px;
            background: #CD235C;
            padding: 0;
            color: #eee;
            font-size: 20px;
            text-align: center;
            font-weight: bold;
        }

        #wrapper {
            position: relative;
            z-index: 1;
            height: 230px;
            width: 100%;
            background: #eee;
            overflow: hidden;
            -ms-touch-action: none;
            /* border: 1px solid red; */
        }

        #scroller {
            /* border: 1px solid blue; */
            list-style: none;
            position: absolute;
            z-index: 1;
            width: 100%;
            height: 240px;
            display: flex;
            flex-direction: row;
            overflow-x: scroll;
            white-space: nowrap;
            /* border: 1px solid blue; */
        }
        /* 针对Webkit内核浏览器隐藏滚动条 */
        /* #scroller::-webkit-scrollbar { 
            display: none; 
        } */

        #scroller li {
            /* border: 1px solid blue; */
            max-width: 220px;
            height: 210px;
            float: left;
            margin: 5px;
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            background-color: #fafafa;
            font-size: 1rem;
            /* overflow: hidden; */
            flex-shrink: 0;
            border-radius: 10px;
        }

        .topic {
            /* border: 1px solid blue; */
            height: 40px;
            background-color: #eee;
            padding: 3px;
            border-radius: 10px 10px 0 0;
            font-weight: bold;
        }

        .topic a {
            /* border: 1px solid red; */
            line-height: 37px;
            padding: 10px 5px 10px 10px;
        }

        .classification {
            /* border: 1px solid blue; */
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            max-height: 170px;
            /* max-height: 270px; */
            border-radius: 0 0 10px 10px;
            overflow-y: scroll;
        }

        .classif_item {
            width: 100px;
            white-space: nowrap;
            /* border: 1px solid purple; */
            padding: 3px;
            padding-left: 10px;
            margin: 3px;
        }
    </style>
</head>

<body>
    <div id="header" onclick="window.location.href='?t1=200'">iScroll</div>
    <div id="wrapper">
        <ul id="scroller">
            <?php
            // 一级分类
            if ($_GET['t1']) {
                $t1 = $_GET['t1'];
                showTopic($_BOOKTAGS, 1, 0);
            }
            // 二级分类
            elseif ($_GET['t2']) {
                $tval = explode('b', $_GET['t2']);
                $t1 = $tval[0];
                $t2 = $tval[1];
                $arr_item = $_BOOKTAGS[$t1][1];
                // var_dump($arr_item);
                showTopic($arr_item, 2, $t1);
            }
            // 三级分类
            elseif ($_GET['t3']) {
                $tval = explode('b', $_GET['t3']);
                $t1 = $tval[0];
                $t2 = $tval[1];
                $arr_item = $_BOOKTAGS[$t1][1];
                showTopic($arr_item, 2, $t1);
            }
            // 列表
            elseif ($_GET['do'] && $_GET['do'] == 'list') {
                showTopic($_BOOKTAGS, 1, 0);
                //sql
                // echo "list";
            }
            ?>
        </ul>
    </div>
</body>

</html>