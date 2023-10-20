<?php
// /////////////////////////////////////////////////////////////////
//
// roa_dev_domain_name_list.php
// 锐拓OA - 信息技术  - 信息技术综合管理  - 续费记录 - 列表
//
// --------------------------------------------------------
//
// Rev.             Author         Logs
// 2023/09/14       Lee          Created
//
// /////////////////////////////////////////////////////////
require_once "Lib/roa.Config.php";

// 验证登录SESSION
if (!CheckLogin()) dieEx("请<a href='roa_log.php?do=log'>登录</a>");

// 检查管理权限
if (!Auth_GetAuth(_AUTH_DEV_DOMAIN_NAME)) dieEx("权限不足，请<a href='javascript:window.history.back(1);'>后退</a>");

// 显示 HTML PAGE
require_once "include/roa.HTML.Top.php";
require_once "include/roa.Header.php";

// 载入常数
require_once "const/roa.Const.Dev.It.php";

// 面包屑导航栏
$sPageTitle = "续费记录";
$aBreadcrumb = array(
    '信息技术',
    '信息技术综合管理',
    "<a href='roa_dev_domain_name_list.php'>域名管理</a>",
    $sPageTitle
);
roaHTMLBreadcrumbNav($aBreadcrumb);

// 设置菜单
echo "<div id='mainleft'>\n";
roaHTMLMenu("dev", "it2");
echo "</div>\n";

// 获取页号
$nPage = 1;
if (isset($_GET ['page']))
    $nPage = (int) $_GET ['page'];
if ($nPage == 0)
    $nPage = 1;

// 设置偏移量
$nPageSize = _PM_PAGES;
$nOffset = ($nPage - 1) * $nPageSize;

$s_order = '  ORDER BY  ';

$creation_date_text = '';
$expire_date_text = '';
if(isset($_GET['order']) && $_GET['order'] == 'creation_date_desc') {
    $s_order .= " A.dtCreationDate DESC ";
    $creation_date_order = 'creation_date_asc'; //当前降序切换为升序
    $creation_date_text = '↓';
}
elseif(isset($_GET['order']) && $_GET['order'] == 'creation_date_asc') {
    $s_order .= " A.dtCreationDate ";
    $creation_date_order = 'creation_date_desc'; //当前升序切换为降序
    $creation_date_text = '↑';
}
elseif(isset($_GET['order']) && $_GET['order'] == 'expire_date_desc') {
    $s_order .= " A.dtExpireDate DESC ";
    $expire_date_order = 'expire_date_asc'; //当前降序切换为升序
    $expire_date_text = '↓';
}
elseif(isset($_GET['order']) && $_GET['order'] == 'expire_date_asc') {
    $s_order .= " A.dtExpireDate ";
    $expire_date_order = 'expire_date_desc'; //当前升序切换为降序
    $expire_date_text = '↑';
}
else {
    $expire_date_order = 'expire_date_desc'; //默认第一次点排序是降序
    $creation_date_order = 'creation_date_desc'; //默认第一次点排序是降序
    $s_order .= " A.nStatus ASC, A.id DESC ";
}
$s_order .= "  LIMIT {$nOffset}, {$nPageSize} ";

$do = Validate::GetPlainText($_GET ['do']);

// WHERE: 组合最终s_whex
$s_whex = ' WHERE ddn.nIsDel=0 ';
// 查询参数显示
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
    //域名
    if ($_GET['sDomainName'] != '') {
        $sDomainName = Validate::GetPlainText($_GET['sDomainName']);
        $sQueryPrompt .= "<p><strong>域名：</strong>{$sDomainName}</p>\n";
        $s_whex .= " AND ddn.sDomainName LIKE '%{$sDomainName}%' ";
    }
    // 域名后缀
    if ($_GET['sDomainSuffix'] != ''){
        $sDomainSuffix = Validate::GetPlainText($_GET['sDomainSuffix']);
        $sQueryPrompt .= "<p><strong>域名关键词：</strong>{$sDomainSuffix}</p>\n";
        $asDomainSuffix = explode(',', $sDomainSuffix);
        for ($i = 0; $i < count($asDomainSuffix); $i++) {
            $aDomainSuffix[$i] = "ddn.sDomainSuffix='{$asDomainSuffix[$i]}'";
        }
        $s_whex .= " AND (" . implode(' OR ', $aDomainSuffix) . ") ";
    }
    // 续费日期from-to
    $dtRenewalDate_from = dateConvertGlobal($_GET ['dtRenewalDate_from']);
    $dtRenewalDate_to = dateConvertGlobal($_GET ['dtRenewalDate_to']);
    if ($dtRenewalDate_from != '') {
        if ($dtRenewalDate_to != '') {
            $sQueryPrompt .= "<p><strong>续费日期：</strong>{$dtRenewalDate_from} 至 {$dtRenewalDate_to}</p>\n";
            $s_whex .= " AND (A.dtRenewalDate BETWEEN '{$dtRenewalDate_from}' AND '{$dtRenewalDate_to}') ";
        } else {
            $sQueryPrompt .= "<p><strong>注册日期：</strong>自 {$dtRenewalDate_from} 以后</p>\n";
            $s_whex .= " AND A.dtRenewalDate>='{$dtRenewalDate_from}' ";
        }
    } else {
        if ($dtRenewalDate_to != '') {
            $sQueryPrompt .= "<p><strong>注册日期：</strong>到 {$dtRenewalDate_to} 之前</p>\n";
            $s_whex .= " AND A.dtRenewalDate<='{$dtRenewalDate_to}' ";
        }
    }
    // 到期日期from-to
    $dtExpireDate_from = dateConvertGlobal($_GET ['dtExpireDate_from']);
    $dtExpireDate_to = dateConvertGlobal($_GET ['dtExpireDate_to']);
    if ($dtExpireDate_from != '') {
        if ($dtExpireDate_to != '') {
            $sQueryPrompt .= "<p><strong>到期日期：</strong>{$dtExpireDate_from} 至 {$dtExpireDate_to}</p>\n";
            $s_whex .= " AND (A.dtExpireDate BETWEEN '{$dtExpireDate_from}' AND '{$dtExpireDate_to}') ";
        } else {
            $sQueryPrompt .= "<p><strong>到期日期：</strong>自 {$dtExpireDate_from} 以后</p>\n";
            $s_whex .= " AND A.dtExpireDate>'{$dtExpireDate_from}' ";
        }
    } else {
        if ($dtExpireDate_to != '') {
            $sQueryPrompt .= "<p><strong>到期日期：</strong>到 {$dtExpireDate_to} 之前</p>\n";
            $s_whex .= " AND A.dtExpireDate<'{$dtExpireDate_to}' ";
        }
    }
    // 续费日期
    if ($_GET['dtRenewalDate'] != '') {
        $dtRenewalDate = Validate::GetPlainText($_GET['dtRenewalDate']);
        $sQueryPrompt .= "<p><strong>续费日期：</strong>{$dtRenewalDate}</p>\n";
        $s_whex .= " AND A.dtRenewalDate = '{$dtRenewalDate}' ";
    }
    // 注册管理平台
    if ($_GET['sManagementPlatform'] != '') {
        $sManagementPlatform = Validate::GetPlainText($_GET['sManagementPlatform']);
        $sQueryPrompt .= "<p><strong>注册管理平台：</strong>{$sManagementPlatform}</p>\n";
        $asManagementPlatform = explode(',', $sManagementPlatform);
        for ($i = 0; $i < count($asManagementPlatform); $i++) {
            $asManagementPlatform[$i] = "ddn.sManagementPlatform='{$asManagementPlatform[$i]}'";
        }
        $s_whex .= " AND (" . implode(' OR ',$asManagementPlatform) . ") ";
    }
    //续费时长
    if($_GET['nDuration'] != ''){
        $nDuration = Validate::GetPlainText($_GET['nDuration']);
        $anDuration = explode(",",$nDuration);
        for($i=0;$i<count($anDuration);$i++){
            $flag = $anDuration[$i];
            $anDuration[$i] = $aDevDomainNameDurationConst[$flag];
            $nanDuration[$i] = "A.nDuration='{$flag}'";
        }
        $anDuration = implode(',' , $anDuration);
        $sQueryPrompt .= "<p><strong>续费时长：</strong>{$anDuration}</p>\n";
        $s_whex .= " AND (" . implode(" OR ",$nanDuration) . ") ";
    }
    //域名所有者
    if ($_GET['sRegistrant'] != '') {
        $sRegistrant = Validate::GetPlainText($_GET['sRegistrant']);
        $sQueryPrompt .= "<p><strong>域名所有者：</strong>{$sRegistrant}</p>\n";
        $asRegistrant = explode(',', $sRegistrant);
        for ($i = 0; $i < count($asRegistrant); $i++) {
            $aRegistrant[$i] = "ddn.sRegistrant='{$asRegistrant[$i]}'";
        }
        $s_whex .= " AND (" . implode(' OR ', $aRegistrant) . ") ";
    }
    //联系邮箱
    if ($_GET['sContactEmail'] != '') {
        $sContactEmail = Validate::GetPlainText($_GET['sContactEmail']);
        $sQueryPrompt .= "<p><strong>联系邮箱：</strong>{$sContactEmail}</p>\n";
        $asContactEmail = explode(',', $sContactEmail);
        for ($i = 0; $i < count($asContactEmail); $i++) {
            $aContactEmail[$i] = "ddn.sContactEmail='{$asContactEmail[$i]}'";
        }
        $s_whex .= " AND (" . implode(' OR ', $aContactEmail) . ") ";
    }
    //状态
    if ($_GET['nStatus'] != '') {
        $snStatus = Validate::GetPlainText($_GET['nStatus']);
        $sStatus = str_replace('10', '已注册', $snStatus);
        $sStatus = str_replace('50', '待注册', $sStatus);
        $sStatus = str_replace('90', '已过期', $sStatus);
        $sQueryPrompt .= $_GET['nStatus_not'] ? "<p><strong>状态：</strong><span class=\"CR\">不含</span> {$sStatus}</p>\n" : "<p><strong>状态：</strong>{$sStatus}</p>\n";
        $aStatus = explode(',', $snStatus);
        for ($i = 0; $i < count($aStatus); $i++) {
            $aStatusa[$i] = "ddn.nStatus='{$aStatus[$i]}'";
        }
        $s_whex .= $_GET['nStatus_not'] ? " AND NOT(" . implode(' OR ', $aStatusa) . ") " : " AND (" . implode(' OR ', $aStatusa) . ") ";
    }
}
// 统计
$sql_total = " SELECT sum(fCost) as total FROM roa_dev_domain_name_renewal_record A  
                    LEFT JOIN roa_dev_domain_name ddn ON A.belongSid=ddn.sId  
                    LEFT JOIN roa_user_account ua ON A.belongUsrId=ua.sUsrId " . $s_whex . $r_order;
$d_conn = DB_Connect();
$d_ret = DB_Query($sql_total, $d_conn);
$info = DB_GetRows($d_ret);
DB_Free($d_ret);
$total = "续费金额总计：" . $info['total'] . "元";
DB_Close($d_conn);
//页码，记录计数
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
                // 快速检索
                $q = Validate::GetPlainText ( $_GET ['q'] );
            ?>
            <form id="qsearch" action="?do=qsearch" method="get" style="display: inline">
                <input type="hidden" name="nStatus" value="<?php echo $_GET["nStatus"]?>" /> 
                <input type="hidden" name="do" value="qsearch" /> 
                <input type="search" name="q" size="45" results="10" autosave="some_unique_value" placeholder="域名" onsearch="javascript:if(this.value=='')window.location='?do=list';" value="<?php echo $q; ?>" /> 
                <input type="submit" class="btn_2 CN" value="搜索" />
                <!-- &nbsp;&nbsp;快速筛选：<a href="?do=search&sManagementPlatform=aliyun.com" class="CN">aliyun.com</a>&nbsp;&nbsp;
                <a href="?do=search&sManagementPlatform=51web.com" class="CN">51web.com</a>&nbsp;&nbsp; -->
            </form>
        </div>

        <!-- LIST -->
<?php
// 查询参数提示框
if ($sQueryPrompt <> '') {
    $sQueryPrompt = "<a style='float:right; color:#c00;' href='?do=list'>取消检索</a>\n" . $sQueryPrompt;
    echo '<div class="validateTips ui-state-highlight" style="margin-bottom:10px">' . $sQueryPrompt . '</div>';
}
if ($nTotal > 0) {
    $confirmDateUrlOrder = getUrlForParameters('order');
    echo "<table class='border_gray' >
        <tr >
            <th width='30'><input type='checkbox' id='chk_all' title='全选'  /></th>
            <th width='165'>域名</th>
            <th width='100'>续费日期</th>
            <th width='100'>续费金额</th>
            <th width='80'>续费时长</th>
            <th width='100'>到期时间</th>
            <th width='100'>注册管理平台</th>
            <th width='80'>登记人</th>
            <th width='160'>登记时间</th>
        </tr>";
    $r_order = " ORDER BY A.dtInsert desc"."  LIMIT {$nOffset}, {$nPageSize} ";
    $sql_select = " SELECT A.*, ddn.*, ua.sName AS sUsrName FROM roa_dev_domain_name_renewal_record A  
                    LEFT JOIN roa_dev_domain_name ddn ON A.belongSid=ddn.sId  
                    LEFT JOIN roa_user_account ua ON A.belongUsrId=ua.sUsrId " .$s_whex .$r_order;

    $d_conn = DB_Connect();
    $d_ret = DB_Query($sql_select, $d_conn);

    while($row = DB_GetRows($d_ret)) {
        $sDomainName = Validate::PutSubStringTips($row['sDomainName'], 22);

        //存放位置
        $sPosition = Validate::PutSubStringTips($row['sPosition'], 10);

        $sStatus = $aDevDomainNameStatusConstColor[$row['nStatus']];

        // 续费时长
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
    // 设置原来的url
    $sUrl = getUrlForPage();
    ShowHTML::PageIndexLongBar($nTotal, $nPage, "?{$sUrl}", $nPageSize);
} 
else {
    echo "<div>暂无信息</div>";
}
?>
    </div><!-- end of #body -->
</div><!-- end of .main -->
<!-- List End -->

<script type="text/javascript">
//选择所有的
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