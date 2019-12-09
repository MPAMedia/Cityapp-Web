<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">

        <div class="row">
            <!-- Message Error -->
            <div class="col-sm-12">
                <?php $this->load->view("backend/include/messages"); ?>
            </div>

        </div>


        <div class="row">

            <div class="col-sm-6">

                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b><?= Translate::sprint("Create new User", "") ?> </b></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form id="form" role="form">


                            <div class="col-sm-12">


                                <div class="form-group required">


                                    <label for="name"><?= Translate::sprint("Images", "") ?>: </label>
                                    <label class="msg-error-form image-data"></label>
                                    <input type="file" name="addimage" id="fileupload"><br>
                                    <div class="clear"></div>
                                    <div id="progress" class="hidden">
                                        <div class="percent" style="width: 0%"></div>
                                    </div>
                                    <div class="clear"></div>


                                    <div id="image-previews">


                                    </div>


                                </div>


                                <div class="form-group">
                                    <label><?= Translate::sprint("Full name") ?> <sup>*</sup> </label>
                                    <input type="text" class="form-control" placeholder="Enter ..." name="name"
                                           id="name">
                                </div>

                                <!-- textarea -->
                                <div class="form-group">
                                    <label><?= Translate::sprint("Email", "") ?> <sup>*</sup></label>
                                    <input type="text" class="form-control" placeholder="Enter ..." name="mail"
                                           id="mail">
                                </div>

                                <div class="form-group">
                                    <label><?= Translate::sprint("Username", "") ?> <sup>*</sup></label>
                                    <input type="text" class="form-control" placeholder="Enter ..." name="username"
                                           id="username">
                                </div>

                                <div class="form-group">
                                    <label><?= Translate::sprint("Password", "") ?> <sup>*</sup></label>
                                    <input type="password" class="form-control" placeholder="Enter ..." name="password"
                                           id="password">
                                </div>

                                <div class="form-group">
                                    <label><?= Translate::sprint("Confirm Password", "") ?> <sup>*</sup></label>
                                    <input type="password" class="form-control" placeholder="Enter ..." name="confirm"
                                           id="confirm">
                                </div>

                                <div class="form-group">
                                    <label> <?= Translate::sprint("Phone Number", "") ?>  </label>
                                    <input type="text" class="form-control" placeholder="Enter ..." name="tel" id="tel">
                                </div>


                                <div class="form-group">
                                    <button type="button" class="btn  btn-primary" id="btnCreate"><span
                                            class="glyphicon glyphicon-check"></span><?= Translate::sprint("Create", "") ?>
                                    </button>
                                    <button type="reset" class="btn  btn-default"><span
                                            class="glyphicon glyphicon-remove"></span> <?= Translate::sprint("Clear", "") ?>
                                    </button>
                                </div>

                            </div>


                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>

            <div class="col-sm-6">

                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b> <?= Translate::sprint("User configuration", "") ?>  </b></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form id="form" role="form">

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label><?= Translate::sprint("Access Role", "") ?> <sup>*</sup></label>
                                    <select id="typeAuth" name="typeAuth" class="form-control select2">
                                        <option value="admin">Admin</option>
                                        <option value="manager">Owner</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label><?= Translate::sprint("Number of stores", "") ?>  </label>
                                    <input type="number" class="form-control" placeholder="10" name="nbr_stores"
                                           id="nbr_stores" value="<?= LIMIT_NBR_STORES ?>">
                                </div>

                                <div class="form-group">
                                    <label> <?= Translate::sprint("Number of events monthly", "") ?>  </label>
                                    <input type="number" class="form-control" placeholder="10" name="nbr_events_monthly"
                                           id="nbr_events_monthly" value="<?= LIMIT_NBR_EVENTS_MONTHLY ?>">
                                </div>

                                <div class="form-group">
                                    <label> <?= Translate::sprint("Campaigns monthly", "") ?></label>
                                    <input type="number" class="form-control" placeholder="10"
                                           name="nbr_campaign_monthly" id="nbr_campaign_monthly"
                                           value="<?= LIMIT_NBR_COMPAIGN_MONTHLY ?>">
                                </div>

                                <div class="form-group">
                                    <label><?= Translate::sprint("Number of offers monthly", "") ?> </label>
                                    <input type="number" class="form-control" placeholder="10" name="nbr_offer_monthly"
                                           id="nbr_offer_monthly" value="<?= LIMIT_NBR_OFFERS_MONTHLY ?>">
                                </div>

                            </div>


                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>

            </div>


        </div>
    </section>

</div>
<script src="<?= base_url("views/skin/backend/plugins/jQuery/jQuery-2.1.4.min.js") ?>"></script>
<script>


    $("#btnCreate").on('click', function () {

        var selector = $(this);
        var name = $("#form #name").val();
        var username = $("#form #username").val();
        var password = $("#form #password").val();
        var confirm = $("#form #confirm").val();
        var mail = $("#form #mail").val();


        var nbr_stores = $("#form #nbr_stores").val();

        var nbr_campaign_monthly = $("#form #nbr_campaign_monthly").val();
        var nbr_events_monthly = $("#form #nbr_events_monthly").val();
        var nbr_offers_monthly = $("#form #nbr_offer_monthly").val();
        var push_campaign_auto = $("#form #push_campaign_auto").val();

        var tel = $("#form #tel").val();
        var typeAuth = $("#form #typeAuth").val();

        var dataSet = {
            "nbr_stores": nbr_stores,
            "nbr_events_monthly": nbr_events_monthly,
            "nbr_campaign_monthly": nbr_campaign_monthly,
            "nbr_offer_monthly": nbr_offers_monthly,
           // "push_campaign_auto": push_campaign_auto,
            "name": name,
            "username": username,
            "password": password,
            "tel": tel,
            "mail": mail,
            "typeAuth": typeAuth,
            "confirm": confirm,
            "image": fileUploaded,
        };

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


        return false;

    });


</script>


<script src="<?= base_url("views/skin/backend/plugins/select2/select2.full.min.js") ?>"></script>
<script>

    $('#typeAuth').select2();

</script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="<?= base_url("views/skin/backend/plugins/uploader/js/jquery.iframe-transport.js") ?>"></script>
<script src="<?= base_url("views/skin/backend/plugins/uploader/js/jquery.ui.widget.js") ?>"></script>
<script src="<?= base_url("views/skin/backend/plugins/uploader/js/jquery.fileupload.js") ?>"></script>

<script>

    <?php

    $token = $this->mUserBrowser->setToken("SUPIMAGES-4555");

    ?>


    Uploader();

    var fileUploaded = {};
    function Uploader() {

        $('#fileupload').fileupload({
            url: "<?=site_url("uploader/ajax/uploadImage")?>",
            sequentialUploads: true,
            formData: {
                'token': "<?=$token?>",
                'ID': "<?=sha1($token)?>"
            },
            dataType: 'json',
            done: function (e, data) {


                console.log(data);
                var results = data._response.result.results;
                $("#progress").addClass("hidden");
                $("#progress .percent").animate({"width": "0%"});
                $(".image-uploaded").removeClass("hidden");
                $("#image-previews").append(results.html);

                fileUploaded[results.image] = results.image;
                //$("#image-data").val(results.image_data);


                $(".image-uploaded #delete").on('click', function () {

                    var nameDir = $(this).attr("data");

                    delete fileUploaded[nameDir];
                    console.log(fileUploaded);


                    $(".image-uploaded.item_" + nameDir).remove();

                    return false;
                });

            },
            fail: function (e, data) {

                $("#progress").addClass("hidden");
                $("#progress .percent").animate({"width": "0%"});

                console.log(data);

            },
            progressall: function (e, data) {

                var progress = parseInt(data.loaded / data.total * 100, 10);

                $("#progress").removeClass("hidden");
                $("#progress .percent").animate({"width": progress + "%"}, "linear");


                console.log(progress);


            },
            progress: function (e, data) {


                var progress = parseInt(data.loaded / data.total * 100, 10);


            },
            start: function (e) {

                $("#fileupload").removeClass("input-error");
                $(".image-data").text("");

            }
        });


    }


</script>



