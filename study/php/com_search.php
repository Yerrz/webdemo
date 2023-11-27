<?php
///////////////////////////////////////////////////////////
//
// roa_admin_sampledir.php
// 锐拓OA - 信息技术 - 文件管理 - 样书内文 - 图书档案管理
//
// Rev.		Author			Logs
// 11/14/2023	Lee			Created
//
///////////////////////////////////////////////////////////
require_once "Lib/roa.Config.php";
require_once "const/roa.Const.Pdm.Bookx.php";

// 验证登录SESSION
if (!CheckLogin()) dieEx("请<a href='roa_log.php?do=log'>登录</a>");
// 检查管理权限
if (!Auth_GetAuth(_AUTH_RIGHTS) && !Auth_GetAuth(_AUTH_BOOKX_FILES_ARCHIVE)) dieEx("权限不足，请<a href='javascript:window.history.back(1);'>后退</a>");

$do = Validate::GetPlainText($_GET['do']);

// -------------
// 显示 HTML PAGE
require_once "include/roa.HTML.Top.php";
require_once "include/roa.Header.php";

//
// 面包屑导航栏
//
$sPageTitle = "检索";
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
<style>
    table .td_title {
        width: 120px;
        text-align: right;
        padding-right: 12px;
    }

    table .td_input {
        width: 450px;
    }

    table tr {
        display: block;
        margin: 10px 0;
    }

    .r-btn {
        padding: 6px 10px;
        font-size: 17px;
    }
    form input.small {width: 80px}
</style>
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
    <div class='body'>
        <form action="request/roa_pdm_bookx_files_archive_data.php?do=search">
            <table>
                <tr>
                    <td class="td_title">合同编号:</td>
                    <td class="td_input"><input type="text" style="width:400px;"></td>
                </tr>
                <tr>
                    <td class="td_title" valign="top">存档人员:</td>
                    <td class="td_input">
                        <div class="div_sUsrId" id="div_sUsrId">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td_title">服务器位置:</td>
                    <td class="td_input">
                        <div class="BtnSet">
                            <?php
                            $name = 'sArchiveLocation';
                            foreach ($sArchiveLocationConst as $key => $value) {
                                echo '<input type="checkbox" name="' . $name . '" id="' . $name . $key . '" value="' . $key . '" /><label for="' . $name . $key . '">' . $value . '</label>';
                            }
                            ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td_title">硬盘路径:</td>
                    <td class="td_input">
                        <div class="BtnSet">
                            <?php
                            $name = 'sDiskPath';
                            foreach ($sDiskPathConst as $key => $value) {
                                echo '<input type="checkbox" name="' . $name . '" id="' . $name . $key . '" value="' . $key . '" /><label for="' . $name . $key . '">' . $value . '</label>';
                            }
                            ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td_title">存档日期:</td>
                    <td class="td_input">
                        <input type="text" autocomplete="off" class="small" name="sRequestDate_from" id="sRequestDate_from"/>
                        至
                        <input type="text" autocomplete="off" class="small" name="sRequestDate_to" id="sRequestDate_to"/>
                    </td>
                </tr>
                <tr>
                    <td class="td_title" valign="top">文件来源:</td>
                    <td class="td_input">
                        <div class="div_sbelongUsrId" id="div_sbelongUsrId">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="td_title">拷贝日期:</td>
                    <td class="td_input">
                        <input type="text" autocomplete="off"  class="small" name="dCopy_from" id="dCopy_from"/>
                        至
                        <input type="text" autocomplete="off"  class="small" name="dCopy_to" id="dCopy_to"/>
                    </td>
                </tr>
                <tr>
                    <td class="td_title" valign="top">操作人员:</td>
                    <td class="td_input">
                        <div class="div_sOperatorId" id="div_sOperatorId">
                        </div>
                    </td>
                </tr>
                <tr style="margin-top:30px;">
                    <td class="td_title"></td>
                    <td class="td_input">
                        <input type="submit" class="r-btn" value="检索">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $.ajax({
            type: "GET",
            url: "request/roa_pdm_bookx_files_archive_data.php",
            data: "do=user&gid=<?php echo _GRP_RIGHTS . ',' . _GRP_RIGHTS_CN . ',' . _GRP_RIGHTS_HK . ',' . _GRP_RIGHTS_OS . ',' . _GRP_TRANS; ?>",
            success: function(msg) {
                var jsonData = $.parseJSON(msg);
                if (jsonData.msg[0] == 'ERROR') {
                    alert(jsonData.msg[1]);
                } else if (jsonData.msg[0] == 'S') {
                    $('#div_sUsrId').append(jsonData.list);
                    $('#div_sbelongUsrId').append(jsonData.list);
                }
            }
        })
        $.ajax({
            type: "GET",
            url: "request/roa_pdm_bookx_files_archive_data.php",
            data: "do=user&gid=<?php echo _GRP_IT; ?>",
            success: function(msg) {
                var jsonData = $.parseJSON(msg);
                if (jsonData.msg[0] == 'ERROR') {
                    alert(jsonData.msg[1]);
                } else if (jsonData.msg[0] == 'S') {
                    $('#div_sOperatorId').append(jsonData.list);
                }
            }
        })

        $("#dCopy_from,#dCopy_to").datepicker({
            dateFormat: 'yy/mm/dd',
            defaultDate: "",
            dayNamesMin: ['日', '一', '二', '三', '四', '五', '六'],
            monthNamesShort: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
            changeMonth: true,
            changeYear: true,
            onClose: function(selectedDate) {
                $("#dCopy_to").datepicker("option", "minDate", selectedDate);
            }
        });
        $("#sRequestDate_from,#sRequestDate_to").datepicker({
            dateFormat: 'yymm',
            defaultDate: "",
            dayNamesMin: ['日', '一', '二', '三', '四', '五', '六'],
            monthNamesShort: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
            changeMonth: true,
            changeYear: true,
            onClose: function(selectedDate) {
                $("#sRequestDate_to").datepicker("option", "minDate", selectedDate);
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