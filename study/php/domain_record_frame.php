<?php
///////////////////////////////////////////////////////////
//
// roa_dev_domain_name_renewal_record_frame.php
// ����OA - ��Ϣ����  - ��Ϣ�����ۺϹ���  - �������� - ���Ѽ�¼
// ע�⣺��ҳ���� IFRAME ����
//
// --------------------------------------------------------
//
// ����			ֵ			����
// do			list,single             �б�,����չʾ
//
// --------------------------------------------------------
//
// Rev.                Author			Logs
// 2023/09/20          Kevin    		Created
//
///////////////////////////////////////////////////////////
require_once "Lib/roa.Config.php";

// ��֤��¼SESSION
if (!CheckLogin()) dieEx("��<a href='roa_log.php?do=log'>��¼</a>");

// ������Ȩ��
if (!Auth_GetAuth(_AUTH_DEV_DOMAIN_NAME)) dieEx("Ȩ�޲��㣬��<a href='javascript:window.history.back(1);'>����</a>");

$do = $_GET['do'];

// ���볣��
require_once "const/roa.Const.Dev.It.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>�������Ѽ�¼</title>
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

// �б�
//
if ($do == 'list') {
    $belongSid = Validate::GetPlainText($_GET['belongSid']);
    
    echo "<p><a class=\"btn_2 CN\"  href=\"?do=insert&belongSid={$belongSid}\">�½�</a></p>";   
    
    // ��ȡҳ��
    $nPage = 1;
    if (isset($_GET['page'])) $nPage = (int)$_GET['page'];
    if ($nPage == 0) $nPage = 1;
    // ����ƫ����
    $nPageSize = _PM_PAGES_S;
    $nOffset = ($nPage - 1) * $nPageSize;
    $s_order = " ORDER BY A.dtExpireDate,A.id  LIMIT {$nOffset}, {$nPageSize} ";
    $s_whex = " WHERE A.belongSid='{$belongSid}' ";
    

    // ��ȡ����
    $sql_count = "SELECT COUNT(*) AS counts FROM roa_dev_domain_name_renewal_record A LEFT JOIN roa_user_account ua ON A.belongUsrId=ua.sUsrId " . $s_whex;
    $d_conn = DB_Connect();
    $d_ret = DB_Query($sql_count, $d_conn);
    $row = DB_GetRows($d_ret); 
    DB_Free($d_ret);
    $nTotal = (int)$row['counts'];
    DB_Close($d_conn);

    // ��ѯ������ʾ��
    if ($sQueryPrompt <> '') {
        $sQueryPrompt = "<a style='float:right; color:#c00;' href='?do=list&belongSid={$belongSid}'>ȡ������</a>\n" . $sQueryPrompt;
        echo '<div class="validateTips ui-state-highlight" style="margin-bottom:10px">' . $sQueryPrompt . '</div>';
    }
    // �б�
    if ($nTotal > 0) {
        echo "<table class='border_gray'>
                <tr>
                    <th width='30'><input type='checkbox' id='chk_all' title='ȫѡ' /></th>
                    <th width='180'>��������</th>
                    <th width='180'>��������</th>
                    <th width='180'>����ʱ��</th>
                    <th width='180'>���ѽ��</th>
                    <th width='50'>�Ǽ���</th>
                    <th width='130'>�Ǽ�ʱ��</th>
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

            // ����ԭ����url
            $sUrl = getUrlForPage();		
            echo "<div class='high20'></div>\n<div>";
            ShowHTML::PageIndexLongBar($nTotal, $nPage, "?{$sUrl}", $nPageSize);
            echo "</div>";
    }
    else echo "<div>������Ϣ��</div>";
}
/**
 * �½�
 */

elseif ($do == 'insert') { 
    $belongSid = Validate::GetPlainText($_GET['belongSid']);
    
    $bValid = true;
    $bValid = $bValid && (strlen($belongSid) == 17); //ԭģ����sId
    
    if (!$bValid) {
        dieEx("����������<a href='javascript:window.history.back(1);'>����</a>");
    }
?>
    <style>
        #reg ul.h li.text {width: 100px;}
        #reg ul.h li.input {width: 550px;}
        #sCorpInfo_sIndvInfo_bak{display: none;}
    </style>
<div id="reg">
<p class="validateTips ui-state-highlight">��ע<span style="color: #F00"> * </span>����ĿΪ������</p>
<form class="roa-form" id="form" action="request/roa_dev_domain_name_data.php?do=renewal_record_insert" method="post">
    <input type="hidden" name="belongSid" id="belongSid" value="<?php echo $belongSid; ?>" />
  <ul class="h">
    <li class="text">����ʱ��</li>
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
    <li class="text">��������</li>
    <li class="redstar">*</li>
    <li class="input">
        <input type="text" autocomplete="off" name="dtExpireDate" id="dtExpireDate" class="ui-widget-content" size="20" value="<?php echo $row['dtExpireDate']; ?>" />
    </li>
  </ul>
  <div class="SP"></div>
  <ul class="h">
    <li class="text">���ѽ��</li>
    <li class="redstar">*</li>
    <li class="input">
        <input type="text"  class="ui-widget-content" name="fCost" id="fCost" value="<?php echo Validate::PutPlainText($row['fCost']); ?>" onKeyUp="inputAmount(this);" onafterpaste="inputAmount(this);" onblur="inputAmountOverFormat(this);" />
    </li>
  </ul>
  <div class="SP"></div>
  <ul class="h">
    <li class="text">��������</li>
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
    	<span class="CP FW">��ע�⣺������Ϣ�ύ���޷����ģ�����ϸ�˲�����������ύ��</span>
    </li>
  </ul>
  <div class="SP"></div>
  <ul class="h">
    <li class="text">&nbsp;</li>
    <li class="redstar">&nbsp;</li>
    <li class="input">
        <input type="submit" class="btn r-btn" value="ȷ���ύ" onclick="return fnBeforeSubmit();" />
        <input type="button" class="btn r-btn" value="ȡ��" onclick="javascript:window.history.back()" />
        <img id="busy_ajaxsubmit" style="display: none" src="images/icon_16x16_spinner.gif" alt="�����ύ" />
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
        dayNamesMin: ['��', 'һ', '��', '��', '��', '��', '��'],
        monthNamesShort: ['һ��','����','����','����','����','����','����','����','����','ʮ��','ʮһ��','ʮ����'],
        timeOnlyTitle: '����ʱ��',
        timeText: 'ʱ��',
        hourText: 'ʱ',
        minuteText: '��',
        secondText: '��',
        currentText: '����',
        closeText: '���',
        minDate:new Date(),
        defaultDate:'+1Y'
    });
    // ��������
    $("#dtRenewalDate").datepicker({
        dateFormat: 'yy-mm-dd',
        timeFormat: 'hh:mm:ss',
        changeMonth: true,
        changeYear: true,
        dayNamesMin: ['��', 'һ', '��', '��', '��', '��', '��'],
        monthNamesShort: ['һ��','����','����','����','����','����','����','����','����','ʮ��','ʮһ��','ʮ����'],
        timeOnlyTitle: '����ʱ��',
        timeText: 'ʱ��',
        hourText: 'ʱ',
        minuteText: '��',
        secondText: '��',
        currentText: '����',
        closeText: '���',
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
                jsonData = { "msg":["ERROR", "�ύδ�ɹ���������������æ"] };
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
    // �ύȷ��
    if (!confirm("��ע�⣺������Ϣ�ύ���޷����ģ�����ϸ�˲�����������ύ��")) return false;
    // ����У��
    $(".roa-form input.text").removeClass('ui-state-error');
    var bValid = true;
    bValid = bValid && checkRequired($("input[name='nDuration']:checked"),"����ʱ��");
    bValid = bValid && checkRequired($("#dtRenewalDate"),"��������");
    bValid = bValid && checkRequired($("#dtExpireDate"),"��������");
    bValid = bValid && checkValRange($("#fCost"),"���ѽ��", 1, 99999);
    return bValid;
}

</script>

</body>
</html>