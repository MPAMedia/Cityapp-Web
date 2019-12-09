<script src="<?= base_url("views/skin/backend/plugins/select2/select2.full.min.js") ?>"></script>

<script>

    $('.DEFAULT_USER_GRPAC, .EMAIL_VERIFICATION, .USER_REGISTRATION, .DEFAULT_USER_MOBILE_GRPAC').select2();

    $('#DEFAULT_USER_GRPAC').val(<?=$config['DEFAULT_USER_GRPAC']?>).trigger('change');
    $('#DEFAULT_USER_MOBILE_GRPAC').val(<?=$config['DEFAULT_USER_MOBILE_GRPAC']?>).trigger('change');

    $(".content .btnSave").on('click', function () {

        var selector = $(this);

        var dataSet = {
            "DEFAULT_USER_GRPAC": $("#DEFAULT_USER_GRPAC").val(),
            "DEFAULT_USER_MOBILE_GRPAC": $("#DEFAULT_USER_MOBILE_GRPAC").val(),
            "EMAIL_VERIFICATION": $("#EMAIL_VERIFICATION").val(),
            "MESSAGE_WELCOME": $("#MESSAGE_WELCOME").val(),
            "USER_REGISTRATION": $("#USER_REGISTRATION").val(),

            <?php foreach ($user_subscribe_fields as $field): ?>
            "<?=$field['config_key']?>": $("#<?=$field['config_key']?>").val(),
            <?php endforeach; ?>
        };

        $.ajax({
            url: "<?=  site_url("ajax/setting/saveAppConfig")?>",
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
                    document.location.reload();
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



