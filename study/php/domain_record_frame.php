<?php
///////////////////////////////////////////////////////////
//
// roa_dev_domain_name_renewal_record_frame.php
// 锐拓OA - 信息技术  - 信息技术综合管理  - 域名管理 - 续费记录
// 注意：此页面由 IFRAME 调用
//
// --------------------------------------------------------
//
// 参数			值			意义
// do			list,single             列表,单个展示
//
// --------------------------------------------------------
//
// Rev.                Author			Logs
// 2023/09/20          Kevin    		Created
//
///////////////////////////////////////////////////////////
require_once "Lib/roa.Config.php";

// 验证登录SESSION
if (!CheckLogin()) dieEx("请<a href='roa_log.php?do=log'>登录</a>");

// 检查管理权限
if (!Auth_GetAuth(_AUTH_DEV_DOMAIN_NAME)) dieEx("权限不足，请<a href='javascript:window.history.back(1);'>后退</a>");

$do = $_GET['do'];

// 载入常数
require_once "const/roa.Const.Dev.It.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>域名续费记录</title>
<?php
echo "<link href=\"{$_DIR}style/jq-ui/jquery-ui-1.8.13.custom.css?" . _SITE_UPDATE . "\" rel=\"stylesheet\" type=\"text/css\" />
<link href=\"{$_DIR}style/main.css?" . _SITE_UPDATE . "\" rel=\"stylesheet\" type=\"text/css\" />
<script type=\"text/javascript\">!window.jQuery && document.write(unescape('%3Cscript src=\"{$_DIR}script/jq/jquery.min.js?ver=1.7.2\" type=\"text/javascript\"%3E%3C/script%3E'));</script>
<script type=\"text/javascript\">!window.jQuery.ui && document.write(unescape('%3Cscript src=\"{$_DIR}script/jq/jquery-ui.min.js?ver=1.8.13\" type=\"text/javascript\"%3E%3C/script%3E'));</script>
<script type=\"text/javascript\" src=\"{$_DIR}script/main.js?" . _SITE_UPDATE . "\"></script>
<script type=\"text/javascript\" src=\"{$_DIR}script/ajaxflipper.roa.js\"></script>
<script type=\"text/javascript\" src=\"{$_DIR}script/jq/jquery.form.js?ver=2.84\"></script>
";
?>
</head>

<body>
<?php 

// 列表
//
if ($do == 'list') {
    $belongSid = Validate::GetPlainText($_GET['belongSid']);
    
    echo "<p><a class=\"btn_2 CN\"  href=\"?do=insert&belongSid={$belongSid}\">新建</a></p>";   
    
    // 获取页号
    $nPage = 1;
    if (isset($_GET['page'])) $nPage = (int)$_GET['page'];
    if ($nPage == 0) $nPage = 1;
    // 设置偏移量
    $nPageSize = _PM_PAGES_S;
    $nOffset = ($nPage - 1) * $nPageSize;
    $s_order = " ORDER BY A.dtExpireDate,A.id  LIMIT {$nOffset}, {$nPageSize} ";
    $s_whex = " WHERE A.belongSid='{$belongSid}' ";
    

    // 获取总数
    $sql_count = "SELECT COUNT(*) AS counts FROM roa_dev_domain_name_renewal_record A LEFT JOIN roa_user_account ua ON A.belongUsrId=ua.sUsrId " . $s_whex;
    $d_conn = DB_Connect();
    $d_ret = DB_Query($sql_count, $d_conn);
    $row = DB_GetRows($d_ret); 
    DB_Free($d_ret);
    $nTotal = (int)$row['counts'];
    DB_Close($d_conn);

    // 查询参数提示框
    if ($sQueryPrompt <> '') {
        $sQueryPrompt = "<a style='float:right; color:#c00;' href='?do=list&belongSid={$belongSid}'>取消检索</a>\n" . $sQueryPrompt;
        echo '<div class="validateTips ui-state-highlight" style="margin-bottom:10px">' . $sQueryPrompt . '</div>';
    }
    // 列表
    if ($nTotal > 0) {
        echo "<table class='border_gray'>
                <tr>
                    <th width='30'><input type='checkbox' id='chk_all' title='全选' /></th>
                    <th width='180'>续费日期</th>
                    <th width='180'>到期日期</th>
                    <th width='180'>续费时长</th>
                    <th width='180'>续费金额</th>
                    <th width='50'>登记人</th>
                    <th width='130'>登记时间</th>
                </tr>";

        $sql = "SELECT A.*, ua.sName AS sUsrName FROM roa_dev_domain_name_renewal_record A LEFT JOIN roa_user_account ua ON A.belongUsrId=ua.sUsrId " . $s_whex . $s_order;
            $d_conn = DB_Connect();
            $d_ret = DB_Query($sql, $d_conn);
            while ($row = DB_GetRows($d_ret)) {
                $sUsrName = Validate::PutSubStringTips($row['sUsrName'], 4);
   
                echo "
                <tr><td colspan='9'><div class='hrdash'></div></td></tr>
                <tr>
                    <td><input id='chk_{$row['id']}' class='select' type='checkbox' value='{$row['id']}' /></td>
                    <td>{$row['dtRenewalDate']}</td>
                    <td>{$row['dtExpireDate']}</td>
                    <td>{$aDevDomainNameDurationConst[$row['nDuration']]}</td>
                    <td>{$row['fCost']}</td>
                    <td>{$sUsrName}</td>
                    <td>{$row['dtInsert']}</td>
                </tr>";
            }
            DB_Free($d_ret);
            DB_Close($d_conn);
            echo "</table>\n";

            // 设置原来的url
            $sUrl = getUrlForPage();		
            echo "<div class='high20'></div>\n<div>";
            ShowHTML::PageIndexLongBar($nTotal, $nPage, "?{$sUrl}", $nPageSize);
            echo "</div>";
    }
    else echo "<div>暂无信息！</div>";
}
/**
 * 新建
 */

elseif ($do == 'insert') { 
    $belongSid = Validate::GetPlainText($_GET['belongSid']);
    
    $bValid = true;
    $bValid = $bValid && (strlen($belongSid) == 17); //原模块编号sId
    
    if (!$bValid) {
        dieEx("参数错误，请<a href='javascript:window.history.back(1);'>后退</a>");
    }
?>
    <style>
        #reg ul.h li.text {width: 100px;}
        #reg ul.h li.input {width: 550px;}
        #sCorpInfo_sIndvInfo_bak{display: none;}
    </style>
<div id="reg">
<p class="validateTips ui-state-highlight">标注<span style="color: #F00"> * </span>的项目为必填项</p>
<form class="roa-form" id="form" action="request/roa_dev_domain_name_data.php?do=renewal_record_insert" method="post">
    <input type="hidden" name="belongSid" id="belongSid" value="<?php echo $belongSid; ?>" />
  <ul class="h">
    <li class="text">续费时长</li>
    <li class="redstar">*</li>
    <li class="input">
       <div class="BtnSet">
          <?php
          echo radioFunction('nDuration', $aDevDomainNameDurationConst, 1);
          ?>
        </div>
    </li>
  </ul>
  <div class="SP"></div>
  <ul class="h">
    <li class="text">到期日期</li>
    <li class="redstar">*</li>
    <li class="input">
        <input type="text" autocomplete="off" name="dtExpireDate" id="dtExpireDate" class="ui-widget-content" size="20" value="<?php echo $row['dtExpireDate']; ?>" />
    </li>
  </ul>
  <div class="SP"></div>
  <ul class="h">
    <li class="text">续费金额</li>
    <li class="redstar">*</li>
    <li class="input">
        <input type="text"  class="ui-widget-content" name="fCost" id="fCost" value="<?php echo Validate::PutPlainText($row['fCost']); ?>" onKeyUp="inputAmount(this);" onafterpaste="inputAmount(this);" onblur="inputAmountOverFormat(this);" />
    </li>
  </ul>
  <div class="SP"></div>
  <ul class="h">
    <li class="text">续费日期</li>
    <li class="redstar">*</li>
    <li class="input">
        <input type="text" autocomplete="off" name="dtRenewalDate" id="dtRenewalDate" class="ui-widget-content" size="20" value="<?php echo $row['dtRenewalDate']; ?>" />
    </li>
  </ul>
  <div class="SP"></div>
  <ul class="h">
    <li class="text">&nbsp;</li>
    <li class="redstar">&nbsp;</li>
    <li class="input">
    	<span class="CP FW">请注意：以上信息提交后将无法更改，请仔细核查无误后再行提交。</span>
    </li>
  </ul>
  <div class="SP"></div>
  <ul class="h">
    <li class="text">&nbsp;</li>
    <li class="redstar">&nbsp;</li>
    <li class="input">
        <input type="submit" class="btn r-btn" value="确认提交" onclick="return fnBeforeSubmit();" />
        <input type="button" class="btn r-btn" value="取消" onclick="javascript:window.history.back()" />
        <img id="busy_ajaxsubmit" style="display: none" src="images/icon_16x16_spinner.gif" alt="正在提交" />
    </li>
  </ul>
  <div class="SP"></div>
</form>
<!-- end #reg --></div>
    <?php 
}
?>
<script type="text/javascript">
$(function() {
    $(".BtnSet").buttonset();
    fnSetChkAll( '#chk_all', '.select' );
    $(".border_gray tr:gt(0)").mouseover(function(){
            $(this).css('background-color','#eee');  
        })
        .mouseout(function(){  
            $(this).css('background-color','white');  
        });
    $("#dtExpireDate").datepicker({
        dateFormat: 'yy-mm-dd',
        timeFormat: 'hh:mm:ss',
        changeMonth: true,
        changeYear: true,
        dayNamesMin: ['日', '一', '二', '三', '四', '五', '六'],
        monthNamesShort: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
        timeOnlyTitle: '设置时间',
        timeText: '时间',
        hourText: '时',
        minuteText: '分',
        secondText: '秒',
        currentText: '现在',
        closeText: '完成',
        minDate:new Date(),
        defaultDate:'+1Y'
    });
    // 续费日期
    $("#dtRenewalDate").datepicker({
        dateFormat: 'yy-mm-dd',
        timeFormat: 'hh:mm:ss',
        changeMonth: true,
        changeYear: true,
        dayNamesMin: ['日', '一', '二', '三', '四', '五', '六'],
        monthNamesShort: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
        timeOnlyTitle: '设置时间',
        timeText: '时间',
        hourText: '时',
        minuteText: '分',
        secondText: '秒',
        currentText: '现在',
        closeText: '完成',
        // minDate:new Date(),
        changeRange:"-5:5",
        defaultDate:'Y'
    });

    $("#form").ajaxForm({
        beforeSubmit: function (a, f, o) {
            $("#busy_ajaxsubmit").show();
            return true;
        },
        success: function (data) {
            $("#busy_ajaxsubmit").hide();
            var jsonData;
            try {
                jsonData = $.parseJSON(data);
            } catch(oException) {
                jsonData = { "msg":["ERROR", "提交未成功，服务器可能正忙"] };
            }
            if (jsonData.msg[0] == "S") {
                parent.location.reload();
            } else {
                updateTips(jsonData.msg[1]);
            }
        }
    });
});

function fnBeforeSubmit() {
    // 提交确认
    if (!confirm("请注意：以上信息提交后将无法更改，请仔细核查无误后再行提交。")) return false;
    // 数据校验
    $(".roa-form input.text").removeClass('ui-state-error');
    var bValid = true;
    bValid = bValid && checkRequired($("input[name='nDuration']:checked"),"续费时长");
    bValid = bValid && checkRequired($("#dtRenewalDate"),"续费日期");
    bValid = bValid && checkRequired($("#dtExpireDate"),"到期日期");
    bValid = bValid && checkValRange($("#fCost"),"付费金额", 1, 99999);
    return bValid;
}

</script>

</body>
</html>