<script src="<?= base_url("views/skin/backend/plugins/select2/select2.full.min.js") ?>"></script>
<script src="<?=  base_url("views/skin/backend/plugins/iCheck/icheck.min.js")?>"></script>
<script>

    //iCheck
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square',
        increaseArea: '20%' // optional
    });


</script>

<?php if(ModulesChecker::isEnabled("pack")): ?>

    <script>


        $('#typeAuth').select2();
        $('#confirm_pack').select2();

        $('#select_pack').select2();
        var pack_id = 0;
        $('#select_pack').on('select2:select', function (e) {


            var data = e.params.data;
            pack_id = data.id;

            $('#modal-default-pack').modal('show');

            $("#_select").on('click', function () {

                var selector = $(this);
                $.ajax({
                    type: 'post',
                    url: "<?=site_url("pack/ajax/changeOwnerPack")?>",
                    data: {
                        'pack_id': data.id,
                        'pack_duration': $("#confirm_pack").val(),
                        'user_id': "<?=$user->id_user?>"
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

    </script>
<?php endif; ?>

<script>


    <?php

    $token = $this->mUserBrowser->setToken("S0XsNOi");

    ?>



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

    $("#form2 #typeAuth").val(<?=$user->grp_access_id?>).trigger('change');

    $(".profle .btnSave").on('click', function () {

        var selector = $(this);

        var password = $("#form1 #password").val();
        var confirm = $("#form1 #confirm").val();
        var name = $("#form1 #name").val();
        var username = $("#form1 #username").val();
        var email = $("#form1 #email").val();
        var phone = $("#form1 #phone").val();

        var dataSet = {
            "id": "<?=$user->id_user?>"/*,"old":old*/,
            "name": name,
            "phone": phone,

            "password": password,
            "confirm": confirm,

            "username": username,
            "email": email,

            "image": <?=$uploader_variable?>,
            "token": "<?=$token?>"
        };

        $.ajax({
            url: "<?=  site_url("ajax/user/profileEdit")?>",
            data: dataSet,
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {
                selector.attr("disabled", true);
            }, error: function (request, status, error) {
                alert(request.responseText);
                selector.attr("disabled", false);
                console.log(request);
            },
            success: function (data, textStatus, jqXHR) {

                selector.attr("disabled", false);
                console.log(data);
                if (data.success === 1) {

                    document.location.href = "<?=admin_url("user/profile")?>";

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

