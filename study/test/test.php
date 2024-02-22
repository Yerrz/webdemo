<?php require_once '../copyrights/lib/conf_booktags.php';?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">

<title>iScroll demo: Event Passthrough</title>

<script type="text/javascript" src="iscroll.js"></script>
<script type="text/javascript" src="../oa/script/jq/jquery.min.js"></script>

<script type="text/javascript">
var booktags = <?php echo json_encode($_BOOKTAGS); ?> ;
const max = 9
$(function(){
    showTopic(booktags);
    console.log("booktags:",booktags);
});

var myScroll;

function loaded () {
	myScroll = new IScroll('#wrapper', { eventPassthrough: true, scrollX: true, scrollY: false, preventDefault: false });
}

// 展示分类主题
function showTopic(arr){
    var scroller_html = '<ul>';
    
    $.each(arr, function(key, val) {
		if(val[0]){
			console.log("count:",Object.keys(val[1]).length);
			// 二级分类个数
			var total = Object.keys(val[1]).length;
			var flag = (total > max) ? true : false;
			console.log("flag:",flag);
			var count = 0;
			// 一级分类标题
			scroller_html += '<li><div class="topic" onclick="showTopic_unfold(' + key +')"><a>' + val[0] + '</a><span>></span></div><div class="classification">';
			$.each(val[1], function(key2, val2) {
                if(val2[0]){
					if(flag){
						count++;
						if(count > max){
							scroller_html += '<div style="display:none;" class="classif_item" id="classif_item_' + key2 + '" onclick="showTopic_item(' + key + ',' + key2 + ')"><label for="classif_item_' + key2 + '">' + val2[0] + '</label></div>';
						}
						else scroller_html += '<div class="classif_item" id="classif_item_' + key2 + '" onclick="showTopic_item(' + key + ',' + key2 + ')"><label for="classif_item_' + key2 + '">' + val2[0] + '</label></div>';
					}
					// 其下所属二级分类
                    // scroller_html += '<div class="classif_item" id="classif_item_' + key2 + '" onclick="showTopic(' + JSON.stringify(val[1]) + ')"><label for="' + key2 + '">' + val2[0] + '</label></div>';
                    else scroller_html += '<div class="classif_item" id="classif_item_' + key2 + '" onclick="showTopic_item(' + key + ',' + key2 + ')"><label for="classif_item_' + key2 + '">' + val2[0] + '</label></div>';
                }
            });
			if(flag) scroller_html += '<div class="classif_item" id="classif_item_more" onclick="showMore(' + key + ')"><label for="classif_item_more" style="font-weight:bold;">...</label></div>';
            scroller_html += '</div></li>';
		}
    });
    scroller_html += '</ul>';
    $("#scroller").html(scroller_html);
}

// 展开二级分类标题
function showTopic_unfold(key){
    var arr_item = Object.values(booktags[key]);
    var arr_item_all = arr_item[1];

    var scroller_html = '<ul>';
    $.each(arr_item_all, function(key, val) {
		if(val[0]){
			// 二级分类标题
			scroller_html += '<li><div class="topic"><a>' + val[0] + '</a><span>></span></div><div class="classification">';
			$.each(val[1], function(key2, val2) {
                // console.log("val2:",val2);
				// 其下所属三级分类
                scroller_html += '<div class="classif_item" id="classif_item_f' + key2 + '" ><label for="classif_item_f' + key2 + '">' + val2 + '</label></div>';
            });
            scroller_html += '</div></li>';
		}
    });
    scroller_html += '</ul>';
    $("#scroller").html(scroller_html);

}
// 二级分类详情
function showTopic_item(key1,key2){
    var arr_item = booktags[key1][1][key2];

    var scroller_html = '<ul>';
    scroller_html += '<li><div class="topic"><a>' + arr_item[0] + '</a><span>></span></div><div class="classification">';
	$.each(arr_item[1], function(k, v) {
        scroller_html += '<div class="classif_item" id="classif_item_s' + k + ')"><label for="classif_item_s' + k + '">' + v + '</label></div>';
    });
    scroller_html += '</div></li>';
    scroller_html += '</ul>';

    $("#scroller").html(scroller_html);
}
</script>

<style type="text/css">
* {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}

body,ul,li {
	padding: 0;
	margin: 0;
	border: 0;
}

body {
	font-size: 12px;
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
	min-height: 240px;
	width: 100%;
	background: #eee;
	overflow: hidden;
	-ms-touch-action: none;
		/* border: 1px solid red; */
}

#scroller {
	/* border: 1px solid blue; */
	position: absolute;
	z-index: 1;
	-webkit-tap-highlight-color: rgba(0,0,0,0);
	min-width: 2100px;
	height: 240px;
	-webkit-transform: translateZ(0);
	-moz-transform: translateZ(0);
	-ms-transform: translateZ(0);
	-o-transform: translateZ(0);
	transform: translateZ(0);
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
	-webkit-text-size-adjust: none;
	-moz-text-size-adjust: none;
	-ms-text-size-adjust: none;
	-o-text-size-adjust: none;
	text-size-adjust: none;
}

#scroller ul {
	list-style: none;
	width: 100%;
	padding: 0;
	margin: 0;
}

#scroller li {
	max-width: 190px;
	height: 220px;
	float: left;
	margin: 5px;
	border-right: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
	background-color: #fafafa;
	font-size: 14px;
	overflow: hidden;
}

.topic {
	height: 40px;
    background-color: #eee;
}
.topic a {
	line-height: 40px;
	padding: 10px;
}
.classification {
    /* border: 1px solid blue; */
    display: flex;
    flex-wrap: wrap;
}
.classif_item {
	width: 85px;
    /* border: 1px solid purple; */
    padding: 3px;
    margin: 3px;
}

</style>
</head>
<body onload="loaded()">
<div id="header">iScroll</div>
<div id="wrapper">
	<div id="scroller">
		<ul>
		<!-- <?php
			require '../copyrights/lib/conf_booktags.php';
            function showTopic($arr){
                foreach($arr as $key=>$val){
                    if($val[0]){
                        echo 
                            '<li>
                                <div class="topic"><a>'. $val[0] . '</a><span>></span></div>
                                <div class="classification">
                            ';
                        foreach($val[1] as $key2 => $val2){
                            echo '<div class="classif_item"><a href="javascript:showTopic('.$val[1].');">'. $val2[0] . '</a></div>';
                        }
                        echo '</div>';
                        echo 
                            '</li>';
                    }
                }
            }
            showTopic($_BOOKTAGS);
			foreach($_BOOKTAGS as $key=>$val){
				if($val[0]){
					echo 
						'<li>
							<div class="topic"><a>'. $val[0] . '</a><span>></span></div>
                            <div class="classification">
						';
					foreach($val[1] as $key2 => $val2){
						echo '<div class="classif_item"><a href="javascript:showTopic('.$val[1].');">'. $val2[0] . '</a></div>';
					}
                    echo '</div>';
					echo 
					    '</li>';
				}
			}
			?> -->
		</ul>
	</div>
</div>
</body>
</html>