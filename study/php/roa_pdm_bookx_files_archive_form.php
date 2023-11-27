<?php
///////////////////////////////////////////////////////////
//
// roa_pdm_bookx_files_archive_form.php
// 锐拓OA - 作品 - 档案存档管理 - 批量新建
//
// Rev.		Author			Logs
// 11/06/2023	Lee			Created
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
if ($do == 'insert') $sPageTitle = "新建";
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
<link rel="stylesheet" href="style/zTreeStyle/zTreeStyle.css" type="text/css">
<script type="text/javascript" src="script/ztree/jquery.ztree.core-3.5.min.js"></script>
<script type="text/javascript" src="script/ztree/jquery.ztree.excheck-3.5.min.js"></script>
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
    <style type="text/css">
        table input {
            width: 180px;
        }

        .ui-widget-content {
            min-height: 20px;
        }

        .contract_title {
            width: 120px;
            text-align: right;
            font-weight: bold;
            font-size: 14px;
        }

        .R {
            color: red;
            font-weight: normal;
        }

        .contract_input {
            width: 500px;
            margin-left: 14px;
            border: 1px solid #cccccc;
            background: #ffffff url(images/ui-bg_flat_75_ffffff_40x100.png) 50% 50% repeat-x;
            color: #333333;
            min-height: 20px;
        }

        #reg ul.h li.text {
            width: 120px;
            text-align: right;
            font-weight: bold;
            font-size: 14px;
        }

        #reg ul.h li.redstar {
            width: 16px;
            color: red;
            text-align: center;
        }

        .ui-widget-content {
            border: 1px solid #cccccc;
            background: #ffffff url(images/ui-bg_flat_75_ffffff_40x100.png) 50% 50% repeat-x;
            color: #333333;
        }

        #reg .input,
        #reg textarea {
            font-size: 14px;
            width: 500px;
        }

        #reg div.SP {
            clear: both;
            padding-top: 12px;
        }

        #reg input.btn {
            font-weight: bold;
            font-size: 17px;
        }

        #reg .SP {
            height: 7px;
        }

        #reg .BtnSet label {
            height: 30px;
        }

        element.style {
            vertical-align: sub;
        }

        .del_button {
            display: inline-block;
            width: 16px;
            height: 16px;
            margin-left: 205px;
            border: none;
            /* background: url(../images/icon_16x16_cross.gif) no-repeat 100% 50%; */
        }

        .ui-autocomplete {
            max-height: 240px;
            overflow-y: auto;
            /* 防止水平滚动条 */
            overflow-x: hidden;
            width: 200px;
        }
    </style>

    <?php
    /////////////////////////
    // 批量新建
    /////////////////////////
    if ($do == 'insert') {
    ?>
        <div class='body'>
            <div>
                <p class="validateTips ui-state-highlight"><strong>分别填写的内容：</strong>您一次最多可以批量新建共20条数据。点击“添加”选择需要增加的合同编号。</p>
                <form id="form" class="roa-form" action="request/roa_pdm_bookx_files_archive_data.php?do=insert" method="post">
                    <div style="margin-bottom: 20px;">
                        <table id="tbl_massins" width="770" border="0" cellspacing="10">
                            <tr class="tr2">
                                <td scope="col" class="contract_title">合同编号 <span class="R">*</span></td>
                                <td>
                                    <input type="hidden" name="sContractId_hidden" class="contract_input" id="sContractId_hidden" />
                                    <input type="text" name="sContractId" class="contract_input" id="sContractId" />
                                    <input type="hidden" name="sContractId_res" class="sContractId_res" id="sContractId_res" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="sIsbnCheckTis CN"></td>
                            </tr>
                        </table>
                        <button type="button" id="btn_add-row" style="position:absolute;left:1320px;top:400px;">添加</button>
                    </div>

                    <div id="reg">
                        <p class="validateTips ui-state-highlight"><strong>统一填写的内容：</strong>以下项目将应用到本次批量新建的所有记录。标注 <span class="R">*</span> 的为必填项。</p>

                        <ul class="h">
                            <li class="text">存档人员</li>
                            <li class="redstar">*</li>
                            <li class="input">
                                <select name="sUsrId" id="sUsrId"></select>
                            </li>
                        </ul>
                        <div class="SP"></div>

                        <ul class="h">
                            <li class="text">服务器位置</li>
                            <li class="redstar">*</li>
                            <li class="input">
                                <div class="BtnSet">
                                    <?php
                                    $name = 'sArchiveLocation';
                                    foreach ($sArchiveLocationConst as $key => $value) {
                                        echo '<input type="radio" name="' . $name . '" id="' . $name . $key . '" value="' . $key . '" /><label for="' . $name . $key . '">' . $value . '</label>';
                                    }
                                    ?>
                                </div>
                            </li>
                        </ul>
                        <div class="SP"></div>

                        <ul class="h">
                            <li class="text">硬盘路径</li>
                            <li class="redstar">*</li>
                            <li class="input">
                                <div class="BtnSet">
                                    <?php
                                    $name = 'sDiskPath';
                                    foreach ($sDiskPathConst as $key => $value) {
                                        echo '<input type="radio" name="' . $name . '" id="' . $name . $key . '" value="' . $key . '" /><label for="' . $name . $key . '">' . $value . '</label>';
                                    }
                                    ?>
                                </div>
                            </li>
                        </ul>
                        <div class="SP"></div>

                        <ul class="h">
                            <li class="text">存档日期</li>
                            <li class="redstar">*</li>
                            <li class="input">
                                <input type="text" name="sRequestDate" id="sRequestDate" class="text ui-widget-content" />
                            </li>
                        </ul>
                        <div class="SP"></div>

                        <ul class="h">
                            <li class="text">文件来源</li>
                            <li class="redstar">*</li>
                            <li class="input">
                                <select name="sbelongUsrId" id="sbelongUsrId"></select>
                            </li>
                        </ul>
                        <div class="SP"></div>

                        <ul class="h">
                            <li class="text">拷贝日期</li>
                            <li class="redstar">*</li>
                            <li class="input">
                                <input type="text" name="dCopy" id="dCopy" class="text ui-widget-content" />
                            </li>
                        </ul>
                        <div class="SP"></div>

                        <ul class="h">
                            <li class="text">操作人员</li>
                            <li class="redstar">*</li>
                            <li class="input">
                                <select name="sOperatorId" id="sOperatorId"></select>
                            </li>
                        </ul>
                        <div class="SP"></div>

                        <ul class="h">
                            <li class="text">备注</li>
                            <li class="redstar">&nbsp;</li>
                            <li class="input">
                                <textarea name="sNote" id="sNote" rows="5" class="wtext ui-widget-content" style="min-height: 200px;"></textarea>
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
                    </div>
                </form>
            </div>
        </div>


    <?php } ?>


    <script type="text/javascript">
        $(function() {
            // // 文件来源
            // belongUsrId_ajax();
            // // 时间组价
            // dCopy_datepicker();
            //合同编号自动完成
            setAC('#sContractId');

            // $('.contract_input').live('keydown', function() {    
            //     var id = $(this).attr('id');
            //     console.log(id);
            //     setAC(id);
            // })

            // 存档人员/文件来源
            // 版权事业部、国内业务部、国际业务部、亚太业务部、翻译事业部
            if ($('#sUsrId').html() == '' || $('#sbelongUsrId').html() == '') {
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
                                $addThisUser = '<option value="' . $_SESSION['USER']['UID'] . '">' . $_SESSION['USER']['NAME'] . '</option>';
                            }
                            ?>
                            addThisUser = '<?php echo $addThisUser; ?>';
                            if ($('#sUsrId').html() == '') $("#sUsrId").html(addThisUser + jsonData.list);
                            if ($('#sbelongUsrId').html() == '') $("#sbelongUsrId").html(addThisUser + jsonData.list);
                        }
                    }
                });
            }

            // 操作人员
            // 网络事业部
            if ($('#sOperatorId').html() == '') {
                $.ajax({
                    type: "GET",
                    url: "request/roa_helper_user.php",
                    data: "do=user&gid=<?php echo _GRP_IT; ?>&su=<?php echo $_SESSION['USER']['UID']; ?>",
                    success: function(msg) {
                        var jsonData = $.parseJSON(msg);
                        if (jsonData.msg[0] == "ERROR") {
                            alert(jsonData.msg[1]);
                        } else if (jsonData.msg[0] == "S") {
                            var addThisUser = '';
                            <?php
                            $addThisUser = '';
                            if (
                                $_SESSION['USER']['GROUP'] != _GRP_IT
                            ) {
                                $addThisUser = '<option value="' . $_SESSION['USER']['UID'] . '">' . $_SESSION['USER']['NAME'] . '</option>';
                            }
                            ?>
                            addThisUser = '<?php echo $addThisUser; ?>';
                            $("#sOperatorId").html(addThisUser + jsonData.list);
                        }
                    }
                });
            }

            // 存档日期
            $('#sRequestDate').datepicker({
                dateFormat: 'yymm',
                defaultDate: "yymm",
                monthNamesShort: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                onClose: function(dateText, inst) {
                    var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                    $(this).val($.datepicker.formatDate('yymm', new Date(year, month, 1)));
                }
            });
            $("#sRequestDate").focus(function () {
                $(".ui-datepicker-calendar").hide();
                $("#ui-datepicker-div").position({
                    my:"center top",
                    at:"center bottom",
                    of: $(this)
                });
            });

            // 拷贝日期
            $("#dCopy").datepicker({
                dateFormat: 'yy/mm/dd',
                defaultDate: "yymmdd",
                dayNamesMin: ['日', '一', '二', '三', '四', '五', '六'],
                monthNamesShort: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
                changeMonth: true,
                changeYear: true,
                showButtonPanel: false,
            });

            //合同编号自动完成
            // $cache_cond = {};
            // $('#contract_input').autocomplete({
            //     source: function(request, response) {
            //         if (request.term in $cache_cond) {
            //             response($cache_cond[request.term]);
            //             return;
            //         }

            //         $.ajax({
            //             type: 'GET',
            //             url: 'request/roa_pdm_bookx_files_archive_data.php?do=contract_input_autocomplete',
            //             data: request,
            //             dataType: 'json',
            //             success: function(data) {
            //                 $cache_cond[request.term] = data;
            //                 // console.log(data);
            //                 response(data);
            //             }
            //         });
            //     },
            //     minLength: 2, //执行搜索前用户必须输入的最小字符数。对于仅带有几项条目的本地数据，通常设置为零，
            //     scroll: true, //当结果集大于默认高度时，是否使用滚动条，Default: true
            //     select: function(event, ui) { // 选中某项时执行的操作
            //         // 存放选中选项的信息
            //         $("#contract_input").val(ui.item.value);
            //         // console.log("您当前已选中label：" + ui.item.label);
            //         // console.log("您当前已选中value：" + ui.item.value);
            //     }

            // })
            //自动加载完
        });


        function setAC(id) {
            $cache_cond = {};
            $(id).autocomplete({
                source: function(request, response) {
                    if (request.term in $cache_cond) {
                        response($cache_cond[request.term]);
                        return;
                    }

                    $.ajax({
                        type: 'GET',
                        url: 'request/roa_pdm_bookx_files_archive_data.php?do=contract_input_autocomplete',
                        data: request,
                        dataType: 'json',
                        success: function(data) {
                            $cache_cond[request.term] = data;
                            // console.log(data);
                            response(data);
                        }
                    });
                },
                minLength: 2, //执行搜索前用户必须输入的最小字符数。对于仅带有几项条目的本地数据，通常设置为零，
                scroll: true, //当结果集大于默认高度时，是否使用滚动条，Default: true
                search: function(event, ui) {
                    $('#sContractId_hidden').val('');
                    console.log("搜索input：", $('#sContractId').val());
                    console.log("搜索input_hidden：", $('#sContractId_hidden').val());
                },
                select: function(event, ui) { // 选中某项时执行的操作
                    // 存放选中选项的信息
                    $(id).val(ui.item.value);
                    $('#sContractId_hidden').val(ui.item.value);
                    // console.log("您当前已选中label：" + ui.item.label);
                    // console.log("您当前已选中value：" + ui.item.value);

                    console.log("选中input：", $('#sContractId').val());
                    console.log("选中input_hidden：", $('#sContractId_hidden').val());
                    //数据库中判重
                    $.ajax({
                        type: 'GET',
                        url: 'request/roa_pdm_bookx_files_archive_data.php',
                        data: 'do=select_database&sContractId_database='+ui.item.value,
                        success:function(msg){
                            $jsonData = $.parseJSON(msg);
                            if($jsonData.msg[0] == "ERROR"){
                                $('#sContractId_res').val(false);
                                alert($jsonData.msg[1]);
                                $('#sContractId').val('');
                            }
                            else if($jsonData.msg[0] == "S"){
                                $('#sContractId_res').val(true);
                            }
                        }
                    })
                }

            })
        }

        // 添加表格
        // $('#btn_add-row')
        //     .button({
        //         icons: {
        //             primary: 'ui-icon-circle-plus'
        //         }
        //     })
        //     .click(function() {
        //         var len = $('.tr').length;
        //         // console.log(len);
        //         if (len < 19) {
        //             var i = len;
        //             for ($k = 0; $k < 3; $k++) {
        //                 i++;
        //                 var row = '<tr class="tr"><td scope="col" class="contract_title"></td><td><input type="text" name="sContractId_' + i + '" class="contract_input"  id="sContractId_' + i +'"/></td></tr><tr><td colspan="2" class="sIsbnCheckTis_' + i + ' CN"></td></tr>';
        //                 $('#tbl_massins').append(row);
        //             }
        //             if (len < 16) {
        //                 var top = parseInt($(this).css("top")) + 133;
        //                 // console.log(top);
        //                 $(this).css({
        //                     "top": top + 'px'
        //                 });
        //             } else {
        //                 $(this).hide();
        //             }
        //         }

        //     });

        // 添加按钮
        $('#btn_add-row')
            .button({
                icons: {
                    primary: 'ui-icon-circle-plus'
                }
            })
            .click(function() {
                if ($('#sContractId_hidden').val() == '') {
                    // 防呆，不能直接输入，只能从下拉列表中选择
                    $('#sContractId').val('');
                    console.log('不能直接输入，请从下拉列表中选择');
                } else {
                    $sContractId = $('#sContractId').val();
                    if ($sContractId != '') {
                        var len = $('.tr').length;
                        if (len < 20) {
                            var flag = $('#sContractId_res').val();
                            console.log(flag);
                            var i = len;
                            i++;
                            $index_id = $('#tbl_massins .contract_input').last().attr('id');
                            console.log('$index_id', $index_id);
                            if ($index_id != 'sContractId') {
                                $index = $index_id.split('_');
                                console.log($index);
                                i = parseInt($index[1]) + 1;
                            }

                            // 判重
                            $asContractId = new Array();
                            $('.tr .contract_input').each(function() {
                                $asContractId.push($(this).val());
                            })
                            if ($.inArray($('#sContractId_hidden').val(), $asContractId) !== -1 ) {
                                console.log('添加重复');
                            }
                            else{
                                if(flag){
                                    var row = '<tr class="tr"><td scope="col" class="contract_title"></td><td><input type="text" name="sContractId_' + i + '" class="contract_input"  id="sContractId_' + i + '" readonly="readonly"/></td><td><a title="点击删除" class="del_button" id="del_button" onclick="del_button(' + i + ')"></a></td></tr>';
                                    // var row = '<tr class="tr"><td scope="col" class="contract_title"></td><td><input type="text" name="sContractId_' + i + '" class="contract_input"  id="sContractId_' + i + '" readonly="readonly"/></td><td><a title="点击删除" class="del_button" id="del_button" onclick="del_button(' + i + ')"></a></td></tr><tr><td colspan="3" class="sIsbnCheckTis_' + i + ' CN"></td></tr>';
                                    $('#tbl_massins').append(row);
                                    $('#sContractId_' + i).val($sContractId);
                                }
                            }
                            console.log('$asContractId:', $asContractId);

                            // var row = '<tr class="tr"><td scope="col" class="contract_title"></td><td><input type="text" name="sContractId_' + i + '" class="contract_input"  id="sContractId_' + i + '"/></td><td><a title="点击删除" class="del_button" id="del_button" onclick="del_button(' + i + ')"></a></td></tr><tr><td colspan="3" class="sIsbnCheckTis_' + i + ' CN"></td></tr>';
                            // $('#tbl_massins').append(row);
                            // $('#sContractId_' + i).val($sContractId);
                        } else {
                            alert("服务器：一次最多添加20条数据");
                        }
                        $('#sContractId').val('');

                        // console.log('len:',len);
                    }
                }
            })

        //删除合同编号行
        function del_button(i) {
            var row = $('#sContractId_' + i).closest('tr');
            row.remove();
        }



        function fnBeforeSubmit() {
            var bValid = true;
            $(".roa-form input.text").removeClass('ui-state-error');

            bValid = bValid && checkRequired($("#sUsrId"), "存档人员");
            bValid = bValid && checkRequired($("input[name='sArchiveLocation']:checked"), "服务器位置");
            bValid = bValid && checkRequired($("input[name='sDiskPath']:checked"), "硬盘路径");
            bValid = bValid && checkRequired($("#sRequestDate"), "存档日期");
            bValid = bValid && checkRequired($("#sbelongUsrId"), "文件来源");
            bValid = bValid && checkRequired($("#dCopy"), "拷贝日期");
            bValid = bValid && checkRequired($("#sOperatorId"), "操作人员");

            return bValid;
        }

        $("#form").ajaxForm({
            beforeSubmit: function(a, f, o) {
                $("#busy_ajaxsubmit").show();
                return true;
            },
            success: function(res) {
                $("#busy_ajaxsubmit").hide();
                var jsonData = $.parseJSON(res);
                if (jsonData.msg[0] == "S") {
                    alert(jsonData.msg[1]);
                    window.location = jsonData.location;
                } else if (jsonData.msg[0] == "ERROR") {
                    alert(jsonData.msg[1]);
                } else {
                    alert("提交未成功，服务器可能正忙");
                }
            }
        });

        // 左边菜单栏收合
        $(function() {
            showLeftMenu('bookx_files_archive');
        });
    </script>
    <?php
    require_once "include/roa.Footer.php";
    ?>