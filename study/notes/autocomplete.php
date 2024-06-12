<?php
//作品评论选项卡-获取评论对象、所在企业
if( $_GET['do'] == 'autocomplete_belongIndvId' ) {
    $term = Validate::GetPlainText($_GET['term']);
    $aArea = array('cn','hk','os','ag');

	foreach ($aArea as $area) {
        $sql = "SELECT A.sIndvId, A.sName, A.sDpt, A.idBelongCorp, B.sName AS sCorpName FROM roa_client_indv_{$area} A LEFT JOIN roa_client_corp_{$area} B ON A.idBelongCorp=B.sCorpId WHERE A.nIsDel=0 AND (A.sName LIKE '%{$term}%') ORDER BY LENGTH(A.sName) LIMIT 10";
		
		$d_ret = DB_Query($sql, $d_conn);
		while ($row = DB_GetRows($d_ret)) {
            $aItem = array(
                'id' => "{$row['sIndvId']}|{$row['idBelongCorp']}|{$row['sCorpName']}|{$area}",
                'label' => "{$row['sName']}" . ($row['sCorpName'] ? " （{$row['sCorpName']}）" : ""),
                'value' => $row['sName'],
            );
            $aResponse[] = $aItem;
            fnStopOnFull( $aResponse );
        }
	}
    die( json_encode($aResponse) );
}
// 用于限制条目总量。判断数据条数达到 15 时终止程序，返回结果
function fnStopOnFull( $aRes ) {
    if ( count($aRes) >= 15 ) die( json_encode($aRes) );
}

?>


<html>

<body>
    <ul class="h">
        <li class="text">评论对象</li>
        <li class="redstar">*</li>
        <li class="input">
            <input type="hidden" class="ui-widget-content" name="sIndvType" id="sIndvType" />
            <input type="hidden" class="ui-widget-content" name="belongIndvId" id="belongIndvId" />
            <input type="text" class="ui-widget-content" name="belongIndvId_input" id="belongIndvId_input" /><br>
            <span class="CM">至少输入 2 个以上字符，系统将自动检索，请从检索结果中选取评论对象</span>
        </li>
    </ul>
    <div class="SP"></div>

    <ul class="h">
        <li class="text">所在企业</li>
        <li class="redstar">*</li>
        <li class="input">
            <input type="hidden" class="ui-widget-content" name="sCorpType" id="sCorpType" />
            <input type="hidden" class="ui-widget-content" name="belongCorpId" id="belongCorpId" />
            <input type="text" class="ui-widget-content" name="belongCorpId_input" id="belongCorpId_input" readonly /><br>
            <span class="CM">无需填写，系统将根据评论对象自动检索所在企业</span>
        </li>
    </ul>
    <div class="SP"></div>
</body>

</html>

<script>
    // 自动完成-评论对象
    var cache_indv_seller = {};
    $("#belongIndvId_input").autocomplete({
        source: function(request, response) {
            if (request.term in cache_indv_seller) {
                response(cache_indv_seller[request.term]);
                return;
            }
            $.ajax({
                url: "request/roa_pdm_bookx_weight_data.php?do=autocomplete_belongIndvId",
                dataType: "json",
                data: request,
                success: function(data) {
                    cache_indv_seller[request.term] = data;
                    response(data);
                }
            });
        },
        minLength: 2,
        search: function(event, ui) {
            // 用户已输入新信息，清空之前自动填写的过期信息
            $('#belongIndvId').val('');
            $('#belongCorpId').val('');
            $('#belongCorpId_input').val('');
            $('#sIndvType').val('');
            $('#sCorpType').val('');
        },
        select: function(event, ui) {
            if (ui.item) {
                var info = ui.item.id.split('|');
                $('#belongIndvId').val(info[0]);
                $('#belongCorpId').val(info[1]);
                $('#belongCorpId_input').val(info[2]);
                $('#sIndvType').val(info[3]);
                $('#sCorpType').val(info[3]);
            }
        }
    }).blur(function() {
        if ($("#belongIndvId_input").val() == "") {
            $("#belongIndvId").val('');
            $("#belongCorpId_input").val('');
            $("#belongCorpId").val('');
            $('#sIndvType').val('');
            $('#sCorpType').val('');
        }
        if ($("#belongIndvId").val() == "") {
            $("#belongIndvId_input").val('');
        }
    });
</script>