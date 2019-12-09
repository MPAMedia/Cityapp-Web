<script src="<?= base_url("views/skin/backend/plugins/colorpicker/bootstrap-colorpicker.js") ?>"></script>
<script src="<?=  base_url("views/skin/backend/plugins/select2/select2.full.min.js")?>"></script>


<script>

    $('.TIME_ZONE').select2();
    $('.ENABLE_STORE_AUTO').select2();
    $('.ENABLE_OFFER_AUTO').select2();
    $('.ENABLE_EVENT_AUTO').select2();
    $('.ENABLE_AUTO_HIDDEN_OFFERS').select2();
    $('.ENABLE_AUTO_HIDDEN_EVENTS').select2();
    $('.PUSH_CAMPAIGNS_WITH_CRON').select2();
    $('.DEFAULT_CURRENCY').select2();
    $('.DEFAULT_LANG').select2();
    $('.EMAIL_VERIFICATION').select2();
    $('.USER_REGISTRATION').select2();
    $('.ENABLE_FRONT_END').select2();
    $('.ENABLE_MESSAGES').select2();
    $('.CHAT_WITH_FIREBASE').select2();
    $('.ALLOW_DASHBOARS_MESSENGER_TO_OWNERS').select2();
    $('.SMTP_SERVER_ENABLED').select2();
    $('.IMAGES_LIMITATION').select2();
    $('.OFFERS_IN_DATE').select2();


    $(".content .btnSave").on('click', function () {

        var selector = $(this);

        var dataSet = {
            <?php

            foreach ($config as $key => $value) {

                if ($key != "APP_LOGO")
                    echo '"' . $key . '" : $(".content #' . $key . '").val(),';
                else
                    echo '"' . $key . '" : '.$uploader_variable.',';

            }

            ?>
            "token": ""
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


</script>
<script>
    $('.colorpicker1').colorpicker();
</script>
<?php if ( (!defined("IOS_PURCHASE_ID") && !defined("IOS_API"))  OR  (!defined("ANDROID_PURCHASE_ID") && !defined("ANDROID_API"))): ?>
    <script>


        $("#second_verify").on("click", function () {

            var selector = $(this);
            var pid = $("#SPID").val();

            if (pid !== "") {

                $.ajax({
                    url: "<?=  site_url("ajax/setting/sverify")?>",
                    data: {
                        pid: pid
                    },
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
                        <?php if(ENVIRONMENT == "development"): ?>
                        console.log(data);
                        <?php endif; ?>
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
                            } else if (data.error) {
                                alert(data.error);
                            }
                        }
                    }
                });


            }

            return true;
        });

    </script>
<?php endif; ?>
