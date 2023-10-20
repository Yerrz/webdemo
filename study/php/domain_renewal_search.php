<?php
// /////////////////////////////////////////////////////////////////
//
// roa_dev_domain_name_list.php
// ����OA - ��Ϣ����  - ��Ϣ�����ۺϹ���  - ���Ѽ�¼ - �б�
//
// --------------------------------------------------------
//
// Rev.             Author         Logs
// 2023/09/14       Lee          Created
//
// /////////////////////////////////////////////////////////
require_once "Lib/roa.Config.php";

// ��֤��¼SESSION
if (!CheckLogin()) dieEx("��<a href='roa_log.php?do=log'>��¼</a>");

// ������Ȩ��
if (!Auth_GetAuth(_AUTH_DEV_DOMAIN_NAME)) dieEx("Ȩ�޲��㣬��<a href='javascript:window.history.back(1);'>����</a>");

// ��ʾ HTML PAGE
require_once "include/roa.HTML.Top.php";
require_once "include/roa.Header.php";

// ���볣��
require_once "const/roa.Const.Dev.It.php";

// ���м������
$sPageTitle = "���Ѽ�¼";
$aBreadcrumb = array(
    '��Ϣ����',
    '��Ϣ�����ۺϹ���',
    "<a href='roa_dev_domain_name_list.php'>��������</a>",
    $sPageTitle
);
roaHTMLBreadcrumbNav($aBreadcrumb);

// ���ò˵�
echo "<div id='mainleft'>\n";
roaHTMLMenu("dev", "it2");
echo "</div>\n";

// ��ȡҳ��
$nPage = 1;
if (isset($_GET ['page']))
    $nPage = (int) $_GET ['page'];
if ($nPage == 0)
    $nPage = 1;

// ����ƫ����
$nPageSize = _PM_PAGES;
$nOffset = ($nPage - 1) * $nPageSize;

$s_order = '  ORDER BY  ';

$creation_date_text = '';
$expire_date_text = '';
if(isset($_GET['order']) && $_GET['order'] == 'creation_date_desc') {
    $s_order .= " A.dtCreationDate DESC ";
    $creation_date_order = 'creation_date_asc'; //��ǰ�����л�Ϊ����
    $creation_date_text = '��';
}
elseif(isset($_GET['order']) && $_GET['order'] == 'creation_date_asc') {
    $s_order .= " A.dtCreationDate ";
    $creation_date_order = 'creation_date_desc'; //��ǰ�����л�Ϊ����
    $creation_date_text = '��';
}
elseif(isset($_GET['order']) && $_GET['order'] == 'expire_date_desc') {
    $s_order .= " A.dtExpireDate DESC ";
    $expire_date_order = 'expire_date_asc'; //��ǰ�����л�Ϊ����
    $expire_date_text = '��';
}
elseif(isset($_GET['order']) && $_GET['order'] == 'expire_date_asc') {
    $s_order .= " A.dtExpireDate ";
    $expire_date_order = 'expire_date_desc'; //��ǰ�����л�Ϊ����
    $expire_date_text = '��';
}
else {
    $expire_date_order = 'expire_date_desc'; //Ĭ�ϵ�һ�ε������ǽ���
    $creation_date_order = 'creation_date_desc'; //Ĭ�ϵ�һ�ε������ǽ���
    $s_order .= " A.nStatus ASC, A.id DESC ";
}
$s_order .= "  LIMIT {$nOffset}, {$nPageSize} ";

$do = Validate::GetPlainText($_GET ['do']);

// WHERE: �������s_whex
$s_whex = ' WHERE ddn.nIsDel=0 ';
// ��ѯ������ʾ
$sQueryPrompt = '';

if ($do == 'qsearch') {
    if (isset($_GET ['q']) && $_GET ['q'] != '') {
        $q = Validate::GetPlainText($_GET ['q']);
        $s_whex .= " AND A.belongSid LIKE '%{$q}%' OR 
            ddn.sDomainName LIKE '%{$q}%'  OR  
            ua.sName LIKE '%{$q}%'  OR
            ddn.sDomainNameKeywords LIKE '%{$q}%'  OR  
            ddn.sManagementPlatform LIKE '%{$q}%'  OR  
            ddn.sDomainSuffix LIKE '%{$q}%' ";
    }
}
elseif ($do == 'search') {
    //����
    if ($_GET['sDomainName'] != '') {
        $sDomainName = Validate::GetPlainText($_GET['sDomainName']);
        $sQueryPrompt .= "<p><strong>������</strong>{$sDomainName}</p>\n";
        $s_whex .= " AND ddn.sDomainName LIKE '%{$sDomainName}%' ";
    }
    // ������׺
    if ($_GET['sDomainSuffix'] != ''){
        $sDomainSuffix = Validate::GetPlainText($_GET['sDomainSuffix']);
        $sQueryPrompt .= "<p><strong>�����ؼ��ʣ�</strong>{$sDomainSuffix}</p>\n";
        $asDomainSuffix = explode(',', $sDomainSuffix);
        for ($i = 0; $i < count($asDomainSuffix); $i++) {
            $aDomainSuffix[$i] = "ddn.sDomainSuffix='{$asDomainSuffix[$i]}'";
        }
        $s_whex .= " AND (" . implode(' OR ', $aDomainSuffix) . ") ";
    }
    // ��������from-to
    $dtRenewalDate_from = dateConvertGlobal($_GET ['dtRenewalDate_from']);
    $dtRenewalDate_to = dateConvertGlobal($_GET ['dtRenewalDate_to']);
    if ($dtRenewalDate_from != '') {
        if ($dtRenewalDate_to != '') {
            $sQueryPrompt .= "<p><strong>�������ڣ�</strong>{$dtRenewalDate_from} �� {$dtRenewalDate_to}</p>\n";
            $s_whex .= " AND (A.dtRenewalDate BETWEEN '{$dtRenewalDate_from}' AND '{$dtRenewalDate_to}') ";
        } else {
            $sQueryPrompt .= "<p><strong>ע�����ڣ�</strong>�� {$dtRenewalDate_from} �Ժ�</p>\n";
            $s_whex .= " AND A.dtRenewalDate>='{$dtRenewalDate_from}' ";
        }
    } else {
        if ($dtRenewalDate_to != '') {
            $sQueryPrompt .= "<p><strong>ע�����ڣ�</strong>�� {$dtRenewalDate_to} ֮ǰ</p>\n";
            $s_whex .= " AND A.dtRenewalDate<='{$dtRenewalDate_to}' ";
        }
    }
    // ��������from-to
    $dtExpireDate_from = dateConvertGlobal($_GET ['dtExpireDate_from']);
    $dtExpireDate_to = dateConvertGlobal($_GET ['dtExpireDate_to']);
    if ($dtExpireDate_from != '') {
        if ($dtExpireDate_to != '') {
            $sQueryPrompt .= "<p><strong>�������ڣ�</strong>{$dtExpireDate_from} �� {$dtExpireDate_to}</p>\n";
            $s_whex .= " AND (A.dtExpireDate BETWEEN '{$dtExpireDate_from}' AND '{$dtExpireDate_to}') ";
        } else {
            $sQueryPrompt .= "<p><strong>�������ڣ�</strong>�� {$dtExpireDate_from} �Ժ�</p>\n";
            $s_whex .= " AND A.dtExpireDate>'{$dtExpireDate_from}' ";
        }
    } else {
        if ($dtExpireDate_to != '') {
            $sQueryPrompt .= "<p><strong>�������ڣ�</strong>�� {$dtExpireDate_to} ֮ǰ</p>\n";
            $s_whex .= " AND A.dtExpireDate<'{$dtExpireDate_to}' ";
        }
    }
    // ��������
    if ($_GET['dtRenewalDate'] != '') {
        $dtRenewalDate = Validate::GetPlainText($_GET['dtRenewalDate']);
        $sQueryPrompt .= "<p><strong>�������ڣ�</strong>{$dtRenewalDate}</p>\n";
        $s_whex .= " AND A.dtRenewalDate = '{$dtRenewalDate}' ";
    }
    // ע�����ƽ̨
    if ($_GET['sManagementPlatform'] != '') {
        $sManagementPlatform = Validate::GetPlainText($_GET['sManagementPlatform']);
        $sQueryPrompt .= "<p><strong>ע�����ƽ̨��</strong>{$sManagementPlatform}</p>\n";
        $asManagementPlatform = explode(',', $sManagementPlatform);
        for ($i = 0; $i < count($asManagementPlatform); $i++) {
            $asManagementPlatform[$i] = "ddn.sManagementPlatform='{$asManagementPlatform[$i]}'";
        }
        $s_whex .= " AND (" . implode(' OR ',$asManagementPlatform) . ") ";
    }
    //����ʱ��
    if($_GET['nDuration'] != ''){
        $nDuration = Validate::GetPlainText($_GET['nDuration']);
        $anDuration = explode(",",$nDuration);
        for($i=0;$i<count($anDuration);$i++){
            $flag = $anDuration[$i];
            $anDuration[$i] = $aDevDomainNameDurationConst[$flag];
            $nanDuration[$i] = "A.nDuration='{$flag}'";
        }
        $anDuration = implode(',' , $anDuration);
        $sQueryPrompt .= "<p><strong>����ʱ����</strong>{$anDuration}</p>\n";
        $s_whex .= " AND (" . implode(" OR ",$nanDuration) . ") ";
    }
    //����������
    if ($_GET['sRegistrant'] != '') {
        $sRegistrant = Validate::GetPlainText($_GET['sRegistrant']);
        $sQueryPrompt .= "<p><strong>���������ߣ�</strong>{$sRegistrant}</p>\n";
        $asRegistrant = explode(',', $sRegistrant);
        for ($i = 0; $i < count($asRegistrant); $i++) {
            $aRegistrant[$i] = "ddn.sRegistrant='{$asRegistrant[$i]}'";
        }
        $s_whex .= " AND (" . implode(' OR ', $aRegistrant) . ") ";
    }
    //��ϵ����
    if ($_GET['sContactEmail'] != '') {
        $sContactEmail = Validate::GetPlainText($_GET['sContactEmail']);
        $sQueryPrompt .= "<p><strong>��ϵ���䣺</strong>{$sContactEmail}</p>\n";
        $asContactEmail = explode(',', $sContactEmail);
        for ($i = 0; $i < count($asContactEmail); $i++) {
            $aContactEmail[$i] = "ddn.sContactEmail='{$asContactEmail[$i]}'";
        }
        $s_whex .= " AND (" . implode(' OR ', $aContactEmail) . ") ";
    }
    //״̬
    if ($_GET['nStatus'] != '') {
        $snStatus = Validate::GetPlainText($_GET['nStatus']);
        $sStatus = str_replace('10', '��ע��', $snStatus);
        $sStatus = str_replace('50', '��ע��', $sStatus);
        $sStatus = str_replace('90', '�ѹ���', $sStatus);
        $sQueryPrompt .= $_GET['nStatus_not'] ? "<p><strong>״̬��</strong><span class=\"CR\">����</span> {$sStatus}</p>\n" : "<p><strong>״̬��</strong>{$sStatus}</p>\n";
        $aStatus = explode(',', $snStatus);
        for ($i = 0; $i < count($aStatus); $i++) {
            $aStatusa[$i] = "ddn.nStatus='{$aStatus[$i]}'";
        }
        $s_whex .= $_GET['nStatus_not'] ? " AND NOT(" . implode(' OR ', $aStatusa) . ") " : " AND (" . implode(' OR ', $aStatusa) . ") ";
    }
}
// ͳ��
$sql_total = " SELECT sum(fCost) as total FROM roa_dev_domain_name_renewal_record A  
                    LEFT JOIN roa_dev_domain_name ddn ON A.belongSid=ddn.sId  
                    LEFT JOIN roa_user_account ua ON A.belongUsrId=ua.sUsrId " . $s_whex . $r_order;
$d_conn = DB_Connect();
$d_ret = DB_Query($sql_total, $d_conn);
$info = DB_GetRows($d_ret);
DB_Free($d_ret);
$total = "���ѽ���ܼƣ�" . $info['total'] . "Ԫ";
DB_Close($d_conn);
//ҳ�룬��¼����
$sql_count = "SELECT COUNT(A.id) AS counts FROM roa_dev_domain_name_renewal_record A
            LEFT JOIN roa_dev_domain_name ddn ON A.belongSid=ddn.sId  
            LEFT JOIN roa_user_account ua ON A.belongUsrId=ua.sUsrId" .$s_whex;
$d_conn = DB_Connect();
$d_ret = DB_Query($sql_count, $d_conn);
$row = DB_GetRows($d_ret);
DB_Free($d_ret);
$nTotal = (int) $row ['counts'];
DB_Close($d_conn);
?>
<div id='main'>
    <div class='header'>
        <div class='fleft'>
            <div class='button_reg_title'>
                <div class='text'>
                    <span class='N'><?php echo $sPageTitle; ?></span>
                </div>
            </div>
        </div>
        <div class='fleft' style='margin-left: 100px; margin-top: 10px;'></div>
        <div class='clear' style='height: 12px;'></div>
    </div>

    <!-- FORM -->
    <div class='body'>
        <div style="margin: 5px 0">
            <?php
                // ���ټ���
                $q = Validate::GetPlainText ( $_GET ['q'] );
            ?>
            <form id="qsearch" action="?do=qsearch" method="get" style="display: inline">
                <input type="hidden" name="nStatus" value="<?php echo $_GET["nStatus"]?>" /> 
                <input type="hidden" name="do" value="qsearch" /> 
                <input type="search" name="q" size="45" results="10" autosave="some_unique_value" placeholder="����" onsearch="javascript:if(this.value=='')window.location='?do=list';" value="<?php echo $q; ?>" /> 
                <input type="submit" class="btn_2 CN" value="����" />
                <!-- &nbsp;&nbsp;����ɸѡ��<a href="?do=search&sManagementPlatform=aliyun.com" class="CN">aliyun.com</a>&nbsp;&nbsp;
                <a href="?do=search&sManagementPlatform=51web.com" class="CN">51web.com</a>&nbsp;&nbsp; -->
            </form>
        </div>

        <!-- LIST -->
<?php
// ��ѯ������ʾ��
if ($sQueryPrompt <> '') {
    $sQueryPrompt = "<a style='float:right; color:#c00;' href='?do=list'>ȡ������</a>\n" . $sQueryPrompt;
    echo '<div class="validateTips ui-state-highlight" style="margin-bottom:10px">' . $sQueryPrompt . '</div>';
}
if ($nTotal > 0) {
    $confirmDateUrlOrder = getUrlForParameters('order');
    echo "<table class='border_gray' >
        <tr >
            <th width='30'><input type='checkbox' id='chk_all' title='ȫѡ'  /></th>
            <th width='165'>����</th>
            <th width='100'>��������</th>
            <th width='100'>���ѽ��</th>
            <th width='80'>����ʱ��</th>
            <th width='100'>����ʱ��</th>
            <th width='100'>ע�����ƽ̨</th>
            <th width='80'>�Ǽ���</th>
            <th width='160'>�Ǽ�ʱ��</th>
        </tr>";
    $r_order = " ORDER BY A.dtInsert desc"."  LIMIT {$nOffset}, {$nPageSize} ";
    $sql_select = " SELECT A.*, ddn.*, ua.sName AS sUsrName FROM roa_dev_domain_name_renewal_record A  
                    LEFT JOIN roa_dev_domain_name ddn ON A.belongSid=ddn.sId  
                    LEFT JOIN roa_user_account ua ON A.belongUsrId=ua.sUsrId " .$s_whex .$r_order;

    $d_conn = DB_Connect();
    $d_ret = DB_Query($sql_select, $d_conn);

    while($row = DB_GetRows($d_ret)) {
        $sDomainName = Validate::PutSubStringTips($row['sDomainName'], 22);

        //���λ��
        $sPosition = Validate::PutSubStringTips($row['sPosition'], 10);

        $sStatus = $aDevDomainNameStatusConstColor[$row['nStatus']];

        // ����ʱ��
        $nDuration = $row['nDuration'];
        $nDuration_true = $aDevDomainNameDurationConst[$nDuration];

        echo "<tr><td colspan='9'><div class='hrdash'></div></td></tr>";
        echo "<tr>
                <td><input id='chk_{$row['id']}' class='select' type='checkbox' value='{$row['id']}'/></td>
                <td><a href='roa_dev_domain_name_renewal_list.php?do=search&sDomainName={$row['sDomainName']}'>{$sDomainName}</a></td>
                <td><a href='roa_dev_domain_name_renewal_list.php?do=search&dtRenewalDate={$row['dtRenewalDate']}'>{$row['dtRenewalDate']}</a></td>
                <td>{$row['fCost']}</td>
                <td>{$nDuration_true}</td>
                <td>{$row['dtExpireDate']}</td>
                <td><a href='roa_dev_domain_name_renewal_list.php?do=search&sManagementPlatform={$row['sManagementPlatform']}'>{$row['sManagementPlatform']}</a></td>
                <td>{$row['sUsrName']}</td>
                <td>{$row['dtInsert']}</td></tr>";
    }
    DB_Free($d_ret);
    DB_Close($d_conn);
    echo "</table>\n <div class='high20'></div>";
    echo "<div><h4>".$total."</h4></div>";
    // ����ԭ����url
    $sUrl = getUrlForPage();
    ShowHTML::PageIndexLongBar($nTotal, $nPage, "?{$sUrl}", $nPageSize);
} 
else {
    echo "<div>������Ϣ</div>";
}
?>
    </div><!-- end of #body -->
</div><!-- end of .main -->
<!-- List End -->

<script type="text/javascript">
//ѡ�����е�
fnSetChkAll( '#chk_all', '.select' );
$("tr:gt(0)").mouseover(function(){
            $(this).css('background-color','#eee');  
        })
        .mouseout(function(){  
            $(this).css('background-color','white');  
        });
</script>
<script type="text/javascript">
$(function() {showLeftMenu('domain_name');});
</script>
<?php
require_once "include/roa.Footer.php";
?>