<script src="<?= base_url("views/skin/backend/plugins/select2/select2.full.min.js") ?>"></script>
<script src="<?=  base_url("views/skin/backend/plugins/iCheck/icheck.min.js")?>"></script>
<script>

    //iCheck
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square',
        increaseArea: '20%' // optional
    });


    var customize_subscription = false;

    $('#customize_subscription').on('ifChecked', function(event){

        $('.form-group.customize_subscription input').attr('disabled',false);
        $('.form-group.customize_subscription select').attr('disabled',false);
        customize_subscription = true;

    });


    $('#customize_subscription').on('ifUnchecked', function(event){

        $('.form-group.customize_subscription input').attr('disabled',true);
        $('.form-group.customize_subscription select').attr('disabled',true);
        customize_subscription = false;

    });

</script>

<?php if(ModulesChecker::isEnabled("pack")): ?>
    <script>

        $('#typeAuth').select2();
        $('#confirm_pack').select2();

        $('#select_pack').select2();

        var pack_id = 0;
        var pack_duration = 0;

        $('#select_pack').on('select2:select', function (e) {

            var data = e.params.data;

            $('#modal-default-pack').modal('show');

            $("#_select").on('click', function () {

                pack_id = data.id;
                pack_duration = $("#confirm_pack").val();

                $('#modal-default-pack').modal('hide');

                return false;
            });
            return true;

        });

        $('a.linkAccess').on('click', function () {
            var url = ($(this).attr('href'));
            var selector = $(this);
            pop(url, selector);

        });

        function pop(url, selector) {

            $.ajax({
                type: 'post',
                url: url,
                dataType: 'json',
                beforeSend: function (xhr) {
                    selector.attr("disabled", true);
                }, error: function (request, status, error) {
                    alert(request.responseText);
                    selector.attr("disabled", false);
                    $('#switcher').modal('hide');
                    $('#modal-default').modal('hide');
                },
                success: function (data, textStatus, jqXHR) {

                    $('#switcher').modal('hide');
                    $('#modal-default').modal('hide');

                    selector.attr("disabled", false);
                    if (data.success === 1) {
                        document.location.reload()
                    } else if (data.success === 0) {
                        var errorMsg = "";
                        for (var key in data.errors) {
                            errorMsg = errorMsg + data.errors[key] + "\n";
                        }
                        if (errorMsg !== "") {
                            alert(errorMsg);
                        }
                    }
                }

            });

            return false;
        }


        function refreshPackSubscription(selector,user_id) {

            $.ajax({
                type: 'post',
                url: "<?=site_url("pack/ajax/changeOwnerPack")?>",
                data: {
                    'pack_id': pack_id,
                    'pack_duration': pack_duration,
                    'user_id': user_id
                },
                dataType: 'json',
                beforeSend: function (xhr) {
                    selector.attr("disabled", true);
                }, error: function (request, status, error) {
                    alert(request.responseText);
                    selector.attr("disabled", false);
                    $('#modal-default-pack').modal('hide');
                },
                success: function (data, textStatus, jqXHR) {

                    $('#modal-default-pack').modal('hide');
                    selector.attr("disabled", false);
                    if (data.success === 1) {
                        document.location.href = "<?=admin_url("user/users")?>";
                    } else if (data.success === 0) {
                        var errorMsg = "";
                        for (var key in data.errors) {
                            errorMsg = errorMsg + data.errors[key] + "\n";
                        }
                        if (errorMsg !== "") {
                            alert(errorMsg);
                        }
                    }
                }

            });


        }


    </script>
<?php endif; ?>


<script>


    $('#typeAuth').select2();
    $("#btnCreate").on('click', function () {


        <?php if(ModulesChecker::isEnabled("pack")): ?>
        if(pack_id === 0 && pack_duration === 0){
            alert("<?=Translate::sprint("Please select a valid pack!")?>");
            return false;
        }
        <?php endif; ?>


        var selector = $(this);
        var name = $("#form #name").val();
        var username = $("#form #username").val();
        var password = $("#form #password").val();
        var confirm = $("#form #confirm").val();
        var email = $("#form #email").val();
        var phone = $("#form #phone").val();

        var typeAuth = $("#form2 #typeAuth").val();

        var dataSet = {
            "name": name,
            "username": username,
            "password": password,
            "phone": phone,
            "email": email,
            "typeAuth": typeAuth,
            "confirm": confirm,
            "image": <?=$uploader_variable?>,
        };




        var user_settings = {};
        <?php if(!ModulesChecker::isEnabled("pack")): ?>
            <?php foreach ($user_subscribe_fields as $field): ?>
                <?php if($field['_display'] == 1): ?>
                    user_settings.<?=$field['field_name']?> = $("#<?=$field['config_key']?>").val();
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>

        if(customize_subscription)
            dataSet.user_settings =  user_settings;


        $.ajax({
            url: "<?=  site_url("ajax/user/create")?>",
            data: dataSet,
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {
                selector.attr("disabled", true);

            }, error: function (request, status, error) {
                alert(request.responseText);
                selector.attr("disabled", false);

                console.log(request.responseText);

            },
            success: function (data, textStatus, jqXHR) {

                console.log(data);

                selector.attr("disabled", false);
                if (data.success === 1) {


                    <?php if(ModulesChecker::isEnabled("pack")): ?>
                        refreshPackSubscription(selector,data.result.id_user);
                    <?php else: ?>
                        document.location.href = "<?=admin_url('user/users')?>";
                    <?php endif; ?>


                } else if (data.success === 0) {
                    var errorMsg = "";
                    for (var key in data.errors) {
                        errorMsg = errorMsg + data.errors[key] + "\n";
                    }
                    if (errorMsg !== "") {
                        alert(errorMsg);
                    }
                }
            }
        });


        return false;

    });

    $('.form-group .form-control.select2').select2();


</script>
