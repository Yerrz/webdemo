<html>

<head>
    <meta charset="utf-8">
</head>

<body>
    <div style="margin-top:5px; position:relative;">
        <button class="btn_8 CN batch-btn batch_alt_belongCrtrId" onclick="loadUsr('belongCrtrId')">批量变更销售员</button>
        <!-- style="display:none"隐藏了这个div -->
        <div class="ui-state-highlight" style="border:1px solid yellow;position:absolute; left:0; width:200px; padding:5px; z-index:99;display:none; ">
            <form class="batch_ops" action="" method="post">
                <input type="hidden" name="idList" class="idList" />
                <select name="belongCrtrId" id="belongCrtrId">
                    <option value="">option1</option>
                    <option value="">option2</option>
                </select>
                <button type="submit" class="r-btn batch-btn-submit">提交</button>
                <button type="button" class="r-btn" onclick="$('.batch_alt_belongCrtrId').next().toggle('blind');">取消</button>
                <span class="result" style="color:#c00"></span>
            </form>
        </div>
    </div>
</body>
<script>
    function loadUsr($a) {
        $('.btn_8 CN batch-btn batch_alt_belongCrtrId').hide();
        $('.ui-state-highlight').style.diplay = 'block';
    }

    function loadUsr(id) {
        if ($('#' + id).html() == '') {
            $.ajax({
                type: "GET",
                url: "request/roa_helper_user.php",
                data: "do=user&gid=<?php echo _GRP_RIGHTS . ',' . _GRP_RIGHTS_CN . ',' . _GRP_RIGHTS_HK . ',' . _GRP_RIGHTS_OS; ?>",
                success: function(msg) {
                    var jsonData = $.parseJSON(msg);
                    if (jsonData.msg[0] == "ERROR") {
                        alert(jsonData.msg[1]);
                    } else if (jsonData.msg[0] == "S") {
                        $('#' + id).html('<option value="">无</option>' + jsonData.list);
                    }
                }
            });
        }
    }
</script>

</html>