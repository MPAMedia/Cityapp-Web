<script src="<?=  base_url("views/skin/backend/plugins/iCheck/icheck.min.js")?>"></script>
<script>

    //iCheck
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square',
        increaseArea: '20%' // optional
    });

    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck("uncheck");
    $('.items .option').iCheck("disable");

    var grp_access = {

    };

    <?php foreach ($actions as $key => $action): ?>
        grp_access.<?=$key?> = {};
        <?php foreach ($action as $value): ?>
            grp_access.<?=$key?>.<?=$value?> = 0;
        <?php endforeach; ?>
    <?php endforeach; ?>


    <?php foreach ($actions as $key => $action): ?>

    $('.items #module_action_<?=$key?>').on('ifUnchecked',function (event) {
        $('.items .option_<?=$key?>').iCheck("disable");
        <?php foreach ($action as $value): ?>
        grp_access.<?=$key?>.<?=$value?> = 0;
        $("#<?=$key?>_<?=$value?>").iCheck("uncheck");
        $("#<?=$key?>_<?=$value?>").on("ifChecked",function (event) {
            grp_access.<?=$key?>.<?=$value?> = 1;
        });
        <?php endforeach; ?>
        return false;
    });

    $('.items #module_action_<?=$key?>').on('ifChecked',function (event) {
        $('.items .option_<?=$key?>').iCheck("enable");
        <?php foreach ($action as $value): ?>
        grp_access.<?=$key?>.<?=$value?> = 1;

        $("#<?=$key?>_<?=$value?>").iCheck("check");
        $("#<?=$key?>_<?=$value?>").on("ifUnchecked",function (event) {
            grp_access.<?=$key?>.<?=$value?> = 0;
        });

        <?php endforeach; ?>
        return false;
    });
    <?php endforeach; ?>


    $("#add_grp_access").on("click",function () {

        var selector = $(this);
        var name = $("#name").val();

        var dataSet = {
            "name": name,
            "grp_access": grp_access
        };



        $.ajax({
            url:"<?=  site_url("ajax/user/add_group_access")?>",
            data:dataSet,
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {
                selector.attr("disabled",true);
            },error: function (request, status, error) {
                alert(request.responseText);
                selector.attr("disabled",false);
                console.log(request)
            },
            success: function (data, textStatus, jqXHR) {

                console.log(data);
                selector.attr("disabled",false);
                if(data.success===1){
                    document.location.href="<?=admin_url("user/group_access")?>";
                }else if(data.success===0){
                    var errorMsg = "";
                    for(var key in data.errors){
                        errorMsg = errorMsg+data.errors[key]+"\n";
                    }
                    if(errorMsg!==""){
                        alert(errorMsg);
                    }
                }
            }
        });

        return false;
    });

</script>