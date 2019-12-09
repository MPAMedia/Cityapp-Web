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


    console.log(grp_access);
    //MODULES LOOP
    <?php foreach ($actions as $key => $action): ?>

    $('.items #module_action_<?=$key?>').on('ifUnchecked',function (event) {
        $('.items .option_<?=$key?>').iCheck("disable");
        $('.items .option_<?=$key?>').iCheck("uncheck");
    });

        //action loop
        <?php foreach ($action as $value): ?>
            $("#<?=$key?>_<?=$value?>").on("ifChecked",function (event) {
                grp_access.<?=$key?>.<?=$value?> = 1;
            });
        <?php endforeach; ?>


    $('.items #module_action_<?=$key?>').on('ifChecked',function (event) {
        $('.items .option_<?=$key?>').iCheck("enable");
        $('.items .option_<?=$key?>').iCheck("check");
    });

        //action loop
        <?php foreach ($action as $value): ?>
            $("#<?=$key?>_<?=$value?>").on("ifUnchecked",function (event) {
                grp_access.<?=$key?>.<?=$value?> = 0;
            });
        <?php endforeach; ?>
        //////


    <?php endforeach; ?>

    var _grp_access = <?=json_encode($permission,JSON_FORCE_OBJECT)?>;

    for (var key in _grp_access) {

        for (var action_key in _grp_access[key]) {
            var action = _grp_access[key][action_key];
            if (action === 1) {
                grp_access[key][action_key] = 1;
            }
        }

    }

    for (var key in grp_access){

        for (var action_key in grp_access[key]){
            var action = grp_access[key][action_key];
            if(action===1){
                //$(".items #module_action_"+key).iCheck('check');
            }
        }

    }

    for (var key in grp_access) {

        for (var action_key in grp_access[key]){
            var action = grp_access[key][action_key];
            if(action===1){
                console.log(grp_access);
                $(".items .options_"+key+".option_"+action_key).iCheck('check');
            }else{
                $(".items .options_"+key+".option_"+action_key).iCheck('uncheck');
            }
        }

    }


    $("#cancel").on('click',function () {
        document.location.href = "<?=admin_url("user/group_access")?>";
        return true;
    });

    $("#add_grp_access").on("click",function () {

        var selector = $(this);
        var name = $("#name").val();

        var dataSet = {
            "id"        : <?=$id?>,
            "name"      : name,
            "grp_access": grp_access
        };


        $.ajax({
            url:"<?=  site_url("ajax/user/edit_group_access")?>",
            data:dataSet,
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {
                selector.attr("disabled",true);
            },error: function (request, status, error) {
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