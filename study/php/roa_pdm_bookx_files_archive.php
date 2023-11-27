<?php
///////////////////////////////////////////////////////////
//
// roa_pdm_bookx_files_archive.php
// 锐拓OA - 作品 - 档案存档管理 - 详情
//
// Rev.		Author			Logs
// 11/10/2023	Lee			Created
//
///////////////////////////////////////////////////////////
require_once "Lib/roa.Config.php";
require_once "const/roa.Const.Pdm.Bookx.php";

// 验证登录SESSION
if (!CheckLogin()) dieEx("请<a href='roa_log.php?do=log'>登录</a>");
// 检查管理权限
if (!Auth_GetAuth(_AUTH_RIGHTS) && !Auth_GetAuth(_AUTH_BOOKX_FILES_ARCHIVE)) dieEx("权限不足，请<a href='javascript:window.history.back(1);'>后退</a>");

$_IS_ADMIN = Auth_GetAuth(_AUTH_RIGHTS) || Auth_GetAuth(_AUTH_BOOKX_FILES_ARCHIVE);

$id = Validate::GetPlainText($_GET['id']);
$sid = Validate::GetPlainText($_GET['sid']);

// -------------
// 显示 HTML PAGE
require_once "include/roa.HTML.Top.php";
require_once "include/roa.Header.php";

//
// 面包屑导航栏
//
$sPageTitle = "详情页面";
$aBreadcrumb = array(
    '作品',
    '档案存档管理',
    $sPageTitle,
);
roaHTMLBreadcrumbNav($aBreadcrumb);

// 设置菜单
echo "<div id='mainleft'>\n";
roaHTMLMenu("pdm", "bookx");
echo "</div>\n";
?>

<?php
$sql = "SELECT A.*,u.sName as sUsrName,b.sName as belongUsrName,o.sName as sOperatorName,i.sName as sName,c.sName as scName,c.belongCrtrId,c.belongOriginalCrtrId,c.belongVitalCrtrId,c.belongUsrId,c.belongOriginalUsrId,c.belongVitalUsrId,c.nStatus,
        aa.sName as belongCrtrName,bb.sName as belongOriginalCrtrName,cc.sName as belongVitalCrtrName,
        dd.sName as belongUsrName2,ee.sName as belongOriginalUsrName,ff.sName as belongVitalUsrName
        from roa_pdm_bookx_files_archive A
            LEFT JOIN roa_user_account u
            ON A.sUsrId=u.sUsrId
            LEFT JOIN roa_user_account b
            ON A.sbelongUsrId=b.sUsrId
            LEFT JOIN roa_user_account o
            ON A.sOperatorId=o.sUsrId
            LEFT JOIN roa_user_account i
            ON A.sEntryKeyersId=i.sUsrId
            LEFT JOIN roa_biz_tim_contract c

            LEFT JOIN roa_user_account aa
            ON c.belongCrtrId=aa.sUsrId
            LEFT JOIN roa_user_account bb
            ON c.belongOriginalCrtrId=bb.sUsrId
            LEFT JOIN roa_user_account cc
            ON c.belongVitalCrtrId=cc.sUsrId
            LEFT JOIN roa_user_account dd
            ON c.belongUsrId=dd.sUsrId
            LEFT JOIN roa_user_account ee
            ON c.belongOriginalUsrId=ee.sUsrId
            LEFT JOIN roa_user_account ff
            ON c.belongVitalUsrId=ff.sUsrId
            ON A.sContractId=c.sId where A.id='{$id}' AND A.sId='{$sid}' ";
$d_conn = DB_Connect();
$query = DB_Query($sql, $d_conn);
$rows = array();
$row = DB_GetRows($query);
?>

<div id='main'>
    <div class='header'>
        <div class='fleft'>
            <div class='button_reg_title'>
                <div class='text'><span class='N'><?php echo $sPageTitle; ?></span></div>
            </div>
        </div>
        <div class='fleft' style='margin-left: 100px; margin-top: 10px;'></div>
        <div class='clear' style='height: 12px;'></div>
    </div>
    <div class='body' style="position:relative;">
        <div class="corp-info" style="position:relative;min-height:360px;">
            <div style="float:left;width: 510px;padding-left: 35px;">
                <h2 display="inline"><span class="N" id="span_sId"><a href="roa_biz_tim_contract.php?sid=<?php echo $row['sContractId']; ?>"><?php echo $row['sContractId']; ?></a></span></h2>
            </div>
            <div style="float:left; width:890px;">
                <ul class="field">
                    <li class="title"><input type="button" class="btn_2 CN" onclick="window.history.back()" value="返回"></li>
                    <li class="data">
                        <?php if(_AUTH_BOOKX_FILES_ARCHIVE_UPDATE == $_SESSION['USER']['UID'] || $_SESSION['USER']['UID'] == 'UA000000000000000'){?>
                            <button onclick="location.href='roa_pdm_bookx_files_archive_form.php?do=update&sId=<?php echo $row['sId']?>'" class="btn_2 CN FW">修改</button>
                        <?php } ?>
                        <?php if($_SESSION['USER']['UID'] == 'UA000000000000000'){?>
                            <button type="button" class="btn_2 CN FW" id="btn_del">删除</button>
                        <?php } ?>
                    </li>
                </ul>
            </div>
            <div class="left" style="float:left;width:395px;">
                <ul class="field">
                    <li class="title">编号</li>
                    <li class="data">
                        <span id="copyText0"><?php echo $row['sId']; ?></span>
                        <button onclick="copyToClipboard('copyText0', '')" class="btn_2 CN FW">复制</button>
                    </li>
                </ul>
                <ul class="field">
                    <li class="title">版权顾问 </li>
                    <li class="data">
                        <?php echo $row['sUsrName']; ?>
                    </li>
                </ul>
                <ul class="field">
                    <li class="title">档案来源</li>
                    <li class="data">
                        <div id="sbelongUsrId_div">
                            <span><?php echo $row['belongUsrName']; ?></span>
                        </div>
                    </li>
                </ul>
                <ul class="field">
                    <li class="title">存档位置</li>
                    <li class="data">
                        <span id="copyText1"><?php echo $sArchiveLocationConst[$row['sArchiveLocation']] . '/' . $sDiskPathConst[$row['sDiskPath']] . '/' . $row['sRequestDate']; ?></span>
                        <button onclick="copyToClipboard('copyText1', '')" class="btn_2 CN FW">复制</button>
                    </li>
                </ul>
                <ul class="field">
                    <li class="title">存档日期</li>
                    <li class="data"><?php echo $row['sRequestDate']; ?></li>
                </ul>
                <ul class="field">
                    <li class="title">拷贝日期</li>
                    <li class="data"><?php echo $row['dCopy']; ?></li>
                </ul>
                <ul class="field">
                    <li class="title">操作人员</li>
                    <li class="data"><?php echo $row['sOperatorName']; ?></li>
                </ul>
                <ul class="field">
                    <li class="title">录入人员</li>
                    <li class="data"><?php echo $row['sName']; ?></li>
                </ul>
            </div>
            <div class="right" style="float:right;width:395px;">
                <ul class="field">
                    <li class="title">合同编号</li>
                    <li class="data"><?php echo $row['sContractId']; ?></li>
                </ul>
                <ul class="field">
                    <li class="title">当前销售员</li>
                    <li class="data"><?php echo $row['belongCrtrName']; ?></li>
                </ul>
                <ul class="field">
                    <li class="title">基础性销售工作</li>
                    <li class="data"><?php echo $row['belongOriginalCrtrName']; ?></li>
                </ul>
                <ul class="field">
                    <li class="title">关键性销售工作</li>
                    <li class="data"><?php echo $row['belongVitalCrtrName']; ?></li>
                </ul>
                <ul class="field">
                    <li class="title">当前采购员</li>
                    <li class="data"><?php echo $row['belongUsrName2']; ?></li>
                </ul>
                <ul class="field">
                    <li class="title">基础性采购工作</li>
                    <li class="data"><?php echo $row['belongOriginalUsrName']; ?></li>
                </ul>
                <ul class="field">
                    <li class="title">关键性采购工作</li>
                    <li class="data"><?php echo $row['belongVitalUsrName']; ?></li>
                </ul>
            </div>
                <ul class="field">
                    <li class="title">备注</li>
                    <li class="data" style="width:200px;"><?php echo $row['sNote']; ?></li>
                </ul>
        </div>
        <!-- 下方功能选项卡 -->
        <div id="tabs" style="position:relative;">
            <ul>
                <li><a href="#tab_sys_info">系统信息</a></li>
            </ul>
            <div id="tab_sys_info" style="min-height: 500px;">
                <div class="narrow-info">
                    <ul class="field">
                        <li class="title">创建时间</li>
                        <li class="data"><?php echo $row['dtInsert']; ?></li>
                    </ul>
                    <ul class="field">
                        <li class="title">更新时间</li>
                        <li class="data"><?php echo $row['dtUpdate']; ?></li>
                    </ul>
                </div>
                <div class="wide-info">
                    <ul class="field">
                        <li class="title">更改记录</li>
                        <li class="data" id="chglog"><button id="btn_chglog_load" class="btn_2 CN">显示</button></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $("#tabs").tabs();
        //显示更改记录
        $('#btn_chglog_load').click(function() {
            $('#chglog').html('<img src="images/icon_16x16_spinner.gif" alt="请稍候..." /> 正在载入');
            $.ajax({
                type: "GET",
                url: "request/roa_pdm_bookx_files_archive_data.php",
                data: "do=chglog&sId=<?php echo $row['sId']; ?>",
                success: function(msg) {
                    $('#chglog').html(msg);
                }
            });
        });

        //文件来源
        if ($('#sbelongUsrId').html() == '') {
            $.ajax({
                type: "GET",
                url: "request/roa_helper_user.php",
                data: "do=user&gid=<?php echo _GRP_RIGHTS . ',' . _GRP_RIGHTS_CN . ',' . _GRP_RIGHTS_HK . ',' . _GRP_RIGHTS_OS . ',' . _GRP_TRANS; ?>&su=<?php echo $_SESSION['USER']['UID']; ?>",
                success: function(msg) {
                    var jsonData = $.parseJSON(msg);
                    if (jsonData.msg[0] == "ERROR") {
                        alert(jsonData.msg[1]);
                    } else if (jsonData.msg[0] == "S") {
                        var addThisUser = '';
                        <?php
                        $addThisUser = '';
                        if (
                            $_SESSION['USER']['GROUP'] != _GRP_RIGHTS
                            && $_SESSION['USER']['GROUP'] != _GRP_RIGHTS_CN
                            && $_SESSION['USER']['GROUP'] != _GRP_RIGHTS_HK
                            && $_SESSION['USER']['GROUP'] != _GRP_RIGHTS_OS
                            && $_SESSION['USER']['GROUP'] != _GRP_TRANS
                        ) {
                            $addThisUser = '<option value="' . $row['sbelongUsrId'] . '">' . $row['belongUsrName'] . '</option>';
                        }
                        ?>
                        addThisUser = '<?php echo $addThisUser; ?>';
                        $("#sbelongUsrId").html(addThisUser + jsonData.list);
                    }
                }
            });
        }
    })

    // //修改按钮显示输入框
    // function showInput($name, $flag) {
    //     $id = '#' + $name;
    //     if ($flag == 1) {
    //         $($id).hide();
    //         $($id + '_chg').show();
    //     } else {
    //         $($id).show();
    //         $($id + '_chg').hide();
    //     }
    // }

    // //修改
    // function submitChangeValue(id) {
    //     var value = $('#' + id).val();
    //     var old_value = $('#input_' + id).val();
    //     console.log('提交修改',value,old_value);
    //     $('#chg_' + id + '_result').html('<img src="images/icon_16x16_spinner.gif" alt="请稍候..." /> 正在提交');
    //     $.ajax({
    //         type: "GET",
    //         url: "request/roa_pdm_bookx_files_archive_data.php?do=update&sId=<?php echo $row['sId']; ?>",
    //         data: {
    //             old_value: old_value,
    //             value: value
    //         },
    //         success: function(msg) {
    //             console.log('ok');
    //             var jsonData = $.parseJSON(msg);
    //             if (jsonData.msg[0] == "ERROR") {
    //                 $('#chg_' + id + '_result').html(jsonData.msg[1]);
    //             } else if (jsonData.msg[0] == "S") {
    //                 alert('变更成功');
    //                 window.location.reload();
    //             } else $('#chg_' + id + '_result').html("变更失败");
    //         }
    //     });
    // }

    //删除
    $('#btn_del').click(function(){
        if (!confirm("确实要删除该档案记录吗？")) return false;
        $.ajax({
			   type: "GET",
			   url: "request/roa_pdm_bookx_files_archive_data.php",
			   data: "do=delete&sId=<?php echo $row['sId']; ?>",
			   success: function(msg) {
				   var jsonData = $.parseJSON(msg);
				   if (jsonData.msg[0] == "ERROR") {
					   alert(jsonData.msg[1]);
				   } else if (jsonData.msg[0] == "S") {
					   window.location = "roa_pdm_bookx_files_archive_list.php?do=list";
				   } else alert("删除失败");
			   }
		});
    })

    // 左边菜单栏收合
    $(function() {
        showLeftMenu('bookx_files_archive');
    });
</script>
<?php
require_once "include/roa.Footer.php";
?>