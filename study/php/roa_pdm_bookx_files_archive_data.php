<?php
///////////////////////////////////////////////////////////
//
// roa_pdm_bookx_files_archive_data.php
// 锐拓OA - 作品 - 档案存档管理
//
// --------------------------------------------------------
//
// Rev.			Author			Logs
// 11/07/2023	Lee			    Created
//
///////////////////////////////////////////////////////////
require_once "../Lib/roa.Config.php";

// 验证登录SESSION
if (!CheckLogin()) dieEx("请<a href='roa_log.php?do=log'>登录</a>");
// 检查管理权限
if (!Auth_GetAuth(_AUTH_RIGHTS) && !Auth_GetAuth(_AUTH_BOOKX_FILES_ARCHIVE)) dieEx("权限不足，请<a href='javascript:window.history.back(1);'>后退</a>");

$do = Validate::GetPlainText($_GET['do']);
$term= Validate::GetPlainText($_GET['term']); //合同编号input
$sId = Validate::GetPlainText($_GET['sId']);  //档案编号


// 批量新建
if($do == 'insert'){

    //获取统一填写内容
    $sUsrId = Validate::GetPlainText($_POST['sUsrId']);
    $sArchiveLocation = Validate::GetPlainText($_POST['sArchiveLocation']);
    $sDiskPath = Validate::GetPlainText($_POST['sDiskPath']);
    Validate::IsDate($_POST['sRequestDate'],"YM") ? $sRequestDate = $_POST['sRequestDate']:die('{"msg":["ERROR","服务器：存档日期输入错误"]}');
    $sbelongUsrId = Validate::GetPlainText($_POST['sbelongUsrId']);

    $dCopy = implode('',explode('/',"{$_POST['dCopy']}"));
    Validate::IsDate($dCopy,"YMD") ? $dCopy = $_POST['dCopy']:die('{"msg":["ERROR","服务器：拷贝时间输入错误"]}');

    $sOperatorId = Validate::GetPlainText($_POST['sOperatorId']);
    $sNote = Validate::GetRichText($_POST['sNote']);

    //获取分别填写的内容
    $sId = getOnlyId(20); // 生成20个不同的ID
    $aarchice = array();
    for($i=1;$i<=20;$i++){
        //忽略分别填写的内容中某项为空的行
        if(strlen($_POST['sContractId_'. $i ]) < 1 ){
            continue;
        }
        else{
            // $dCopy_post = implode('',explode('/',"{$_POST['dCopy_'. $i ]}"));
            // Validate::IsDate($dCopy_post,"YMD") ? $dCopy = $_POST['dCopy_'. $i ]:die('{"msg":["ERROR","服务器：拷贝时间输入错误"]}');
            $aarchice[] = array(
                'sId' => 'BF'.$sId[$i],
                'sContractId' => Validate::GetPlainText($_POST['sContractId_'. $i ]),
            );
        }
    }
    if(empty($aarchice)){
        die('{"msg":["ERROR","服务器：至少填写一行数据"]}');
    }
    else{
        $sql = "INSERT INTO roa_pdm_bookx_files_archive(`sId`,`sUsrId`,`sContractId`,`sArchiveLocation`,`sDiskPath`,`sRequestDate`,`sbelongUsrId`,`dCopy`,`sOperatorId`,`sEntryKeyersId`,`dtInsert`,`sNote`) VALUES";
        foreach($aarchice as $info){
            $values[] = "('{$info['sId']}','{$sUsrId}','{$info['sContractId']}','{$sArchiveLocation}','{$sDiskPath}','{$sRequestDate}','{$sbelongUsrId}','{$dCopy}','{$sOperatorId}','{$_SESSION['USER']['UID']}',NOW(),'{$sNote}')";
        }
        $sql .= implode(',' , $values);
        $d_conn = DB_Connect();
        DB_Query($sql, $d_conn);
        DB_Close($d_conn);
        die('{ "msg":["S", "服务器：提交成功"],"location":"roa_pdm_bookx_files_archive_list.php?do=list"}');
    }

    // print_r($aarchice);

}
//合同编号自动完成
if($do == 'contract_input_autocomplete'){
    $sql = "SELECT `id`,`sId`,`sName` FROM roa_biz_tim_contract where `sId` LIKE '%{$term}%'";
    $d_conn = DB_Connect();
    $query = DB_Query($sql,$d_conn);
    while($row = DB_GetRows($query)){
        $aItem = array(
            "id" => "{$row['id']}",
            "label" => "[{$row['sId']}] {$row['sName']}",
            "value" => "{$row['sId']}"
        );
        $Response[] = $aItem;
    }
    die(json_encode($Response));
}

//系统信息-更改记录显示
if($do == 'chglog'){
    $sql = "SELECT `sDetail` FROM roa_pdm_bookx_files_archive where `sId`='{$sId}' AND nIsDel=0 ";
    $d_conn = DB_Connect();
    $query = DB_Query($sql,$d_conn);
    $info = DB_GetRows($query);
    $sDetail = json_decode($info['sDetail']);
    if ($sDetail != '') {
        $sDetail = array_reverse($sDetail);
        echo "<ul>";
        foreach ($sDetail as $val) {
            $user = '("'.$val[1].'","'.$val[3].'","'.$val[4].'")';
            // echo $user;

            $sql_select = "SELECT sUsrId,sName FROM roa_user_account where sUsrId IN {$user} ";
            // echo $sql_select;
            $d_conn = DB_Connect();
            $query_select = DB_Query($sql_select,$d_conn);
            $auser = array();
            while($info_select = DB_GetRows($query_select)){
                $auser[$info_select['sUsrId']] = $info_select['sName'];
            }
            // print_r($auser);

            echo "<li>";
            printf("%s 由  %s  更改", $val[0], $auser[$val[1]]);

            printf("</li><li>1、%s（%s → %s）", $val[2], $auser[$val[3]], $auser[$val[4]]);
            echo "</li><br>";
        }
        echo "</ul>";
        DB_Free($query);
        DB_Close($d_conn);
    }
}

//修改
if($do == 'update'){
    $value = Validate::GetPlainText($_GET['value']);
    $old_value = Validate::GetPlainText($_GET['old_value']);

    if($value != $old_value){
        date_default_timezone_set("Asia/Shanghai");
        $dtUpdate = date("Y-m-d H:i:s",time());
    
        $sdetail = array();
        array_push($sdetail,$dtUpdate,$_SESSION['USER']['UID'],'文件来源',$old_value,$value);
        // print_r($sdetail);
    
    
        $sql = "SELECT `sDetail` FROM roa_pdm_bookx_files_archive where `sId`='{$sId}' ";
        $d_conn = DB_Connect();
        $query = DB_Query($sql,$d_conn);
        $info = DB_GetRows($query);
        $sDetail = $info['sDetail'];
        if($sDetail == ''){
            $sDetail = array();
        }
        else{
            $sDetail = json_decode($sDetail);
        }
    
        $sDetail[] = $sdetail;
        //addslashes:给unicode编码加上了\,以便解码
        $sDetail = addslashes( json_encode($sDetail) );
    
        $sql_chglog = "UPDATE roa_pdm_bookx_files_archive SET sbelongUsrId='{$value}',sDetail='{$sDetail}',dtUpdate='{$dtUpdate}' where sId='{$sId}'";
        DB_Query($sql_chglog, $d_conn);
        
        die('{ "msg":["S", "服务器：更改成功"] }');
    }
    else{
        die('{ "msg":["FAIL", "服务器：更改内容不变"] }');
    }

}

//详情页删除
if($do == 'delete'){
    if (!Auth_GetAuth(_AUTH_RIGHTS) && !Auth_GetAuth(_AUTH_BOOKX_FILES_ARCHIVE)) die('{ "msg":["ERROR", "服务器：权限不足"] }');
    $sql = "UPDATE roa_pdm_bookx_files_archive SET nIsDel=1 where sId='{$sId}'";
    $d_conn = DB_Connect();
    DB_Query($sql,$d_conn);
    DB_Close($d_conn);

    die('{ "msg":["S", "服务器：删除成功"] }');
}

//获取存档人所有用户checkbox
if($do == 'user'){
    $gid = Validate::GetPlainText($_GET['gid']);
    $type = Validate::GetPlainText($_GET['type']);
    $aGid = explode(',', $gid);
    $sql = "SELECT sUsrId, sName, belongGrpId, nStatus FROM roa_user_account WHERE nStatus<2 AND  sUsrId IN (SELECT sUsrId FROM roa_user_account_role WHERE belongGrpId IN ('".JOIN("','", $aGid)."')) ";
    $d_conn = DB_Connect();
    $query = DB_Query($sql,$d_conn);
    while($rows = DB_GetRows(($query))){
        $checked = '';
        if($rows['belongGrpId']==_GRP_ADMIN AND $rows['nStatus'] != 0) continue; //行政人事部正常状态的账号才需要展示出来
        $list .= "<div style='width:100px;display:inline-block'><input type='checkbox' name='{$type}' id='{$type}' value='{$rows['sUsrId']}'{$checked}>{$rows['sName']}</div>";
    }
    // print_r($list);
    DB_Free($query);
    DB_Close($d_conn);

    printf('{ "msg":["S", "用户列表获取成功"], "list":"%s" }', $list);
}

// //检索
// if($do == 'search'){
//     $s_whex = ' WHERE nIsDel=0';
//     //存档人员
//     if ($_GET['sUsr'] != '') {
//         $sUsr = Validate::GetPlainText($_GET['sUsr']);
//         $sQueryPrompt .= "<p><strong>存档人员：</strong>{$sUsr}</p>\n";
//         $asUsr = explode(',', $sUsr);
//         for ($i = 0; $i < count($asDomainSuffix); $i++) {
//             $asUsr[$i] = "A.sUsrId LIKE '{$asUsr[$i]}'";
//         }
//         $s_whex .= " AND (" . implode(' OR ', $asUsr) . ") ";
//     }
//     //服务器位置
//     if ($_GET['sArchiveLocation'] != '') {
//         $sArchiveLocation = Validate::GetPlainText($_GET['sArchiveLocation']);
//         $sQueryPrompt .= "<p><strong>服务器位置：</strong>{$sArchiveLocation}</p>\n";
//         $asArchiveLocation = explode(',', $sArchiveLocation);
//         for ($i = 0; $i < count($asArchiveLocation); $i++) {
//             $asArchiveLocation[$i] = "A.sArchiveLocation='{$asArchiveLocation[$i]}'";
//         }
//         $s_whex .= " AND (" . implode(' OR ', $asArchiveLocation) . ") ";
//     }
//     //硬盘路径
//     if ($_GET['sDiskPath'] != '') {
//         $sDiskPath = Validate::GetPlainText($_GET['sDiskPath']);
//         $sQueryPrompt .= "<p><strong>硬盘路径：</strong>{$sDiskPath}</p>\n";
//         $asDiskPath = explode(',', $sDiskPath);
//         for ($i = 0; $i < count($asDiskPath); $i++) {
//             $asDiskPath[$i] = "A.sDiskPath='{$asDiskPath[$i]}'";
//         }
//         $s_whex .= " AND (" . implode(' OR ', $asDiskPath) . ") ";
//     }
//     //文件来源
//     if ($_GET['sbelongUsr'] != '') {
//         $sbelongUsr = Validate::GetPlainText($_GET['sbelongUsr']);
//         $sQueryPrompt .= "<p><strong>文件来源：</strong>{$sbelongUsr}</p>\n";
//         $asbelongUsr = explode(',', $sbelongUsr);
//         for ($i = 0; $i < count($asbelongUsr); $i++) {
//             $asbelongUsr[$i] = "A.sbelongUsrId='{$asbelongUsr[$i]}'";
//         }
//         $s_whex .= " AND (" . implode(' OR ', $asbelongUsr) . ") ";
//     }
//     //操作人员
//     if ($_GET['sOperator'] != '') {
//         $sOperator = Validate::GetPlainText($_GET['sOperator']);
//         $sQueryPrompt .= "<p><strong>操作人员：</strong>{$sOperator}</p>\n";
//         $asOperator = explode(',', $sOperator);
//         for ($i = 0; $i < count($asOperator); $i++) {
//             $asOperator[$i] = "A.sOperatorId='{$asOperator[$i]}'";
//         }
//         $s_whex .= " AND (" . implode(' OR ', $asOperator) . ") ";
//     }
//     $sql = "SELECT A.*,u.sName as sUsrName,b.sName as belongUsrName,o.sName as sOperatorName,i.sName as sName from roa_pdm_bookx_files_archive A
//             LEFT JOIN roa_user_account u
//             ON A.sUsrId=u.sUsrId
//             LEFT JOIN roa_user_account b
//             ON A.sbelongUsrId=b.sUsrId
//             LEFT JOIN roa_user_account o
//             ON A.sOperatorId=o.sUsrId
//             LEFT JOIN roa_user_account i
//             ON A.sEntryKeyersId=i.sUsrId" .$s_whex;

// }
?>