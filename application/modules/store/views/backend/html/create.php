<?php


$categories = $categories[Tags::RESULT];
$places = $places[Tags::RESULT];

if($this->session->has_userdata("latitude")){
    $lat = $this->session->userdata("latitude");
}else{
    $lat = MAP_DEFAULT_LATITUDE;
}

if($this->session->has_userdata("longitude")){
    $lng = $this->session->userdata("longitude");
}else{
    $lng = MAP_DEFAULT_LONGITUDE;
}



?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content">
        <div class="row">
            <!-- Message Error -->
            <div class="col-sm-12">
                <?php $this->load->view("backend/include/messages"); ?>
            </div>

        </div>

        <div class="row">

            <form id="form" role="form">
                <div class="col-md-6">

                    <div class="row">
                        <div class="col-md-12">

                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><b> <?= Translate::sprint("Store photos") ?></b></h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">

                                    <!-- text input -->

                                    <div class="form-group required">


                                        <label class="nsup-fileuploadlabel" for="nsup-photogallery">
                                            <span id="fileuploadbtn" class="nsup-btn"><strong><?=Translate::sprint("Select Photo")?></strong></span>
                                            <span><?=Translate::sprintf("Maximum upload file size: %s",array(MAX_IMAGE_UPLOAD." MB"))?></span>
                                            <input id="fileuploadinput" class="nsup-fileinput" type="file" name="addimage">
                                        </label>

                                        <label class="msg-error-form image-data"></label>
                                        <div class="clear"></div>
                                        <div id="progress" class="hidden">
                                            <div class="percent" style="width: 0%"></div>
                                        </div>
                                        <div class="clear"></div>


                                        <div id="image-previews">


                                        </div>


                                    </div>

                                </div>
                                <!-- /.box-body -->
                            </div>

                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><b> <?= Translate::sprint("Create your store") ?></b></h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">

                                    <!-- text input -->

                                    <div class="form-group">
                                        <label><?= Translate::sprint("Name", "") ?> : </label>
                                        <input type="text" class="form-control"
                                               placeholder="<?= Translate::sprint("Enter") ?> ..." name="name" id="name">
                                    </div>
                                    <div class="form-group">
                                        <label><?= Translate::sprint("AR Name", "") ?> : </label>
                                        <input type="text" class="form-control"
                                               placeholder="<?= Translate::sprint("Enter Arabic") ?> ..." name="name_ar" id="name_ar">
                                    </div>

                                    <div class="form-group">
                                        <label><?= Translate::sprint("Category", "") ?> :</label>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <select id="cat" name="cat" class="form-control selectCat select2">
                                                    <?php if (!empty($categories)) { ?>

                                                        <?php foreach ($categories AS $cat) { ?>

                                                            <option value="<?= $cat['id_category'] ?>"><?= $cat['name'] ?></option>


                                                        <?php } ?>
                                                    <?php } ?>

                                                </select>
                                            </div>

                                            <div class="col-lg-3">

                                            </div>

                                        </div>
                                        <!--                      <button type="button" class="btn btn-xs"><span class="glyphicon glyphicon-plus"></span></button>-->


                                    </div>
                                    <!-- Place-->
                                    <div class="form-group">
                                        <label><?= Translate::sprint("Place", "") ?> :</label>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <select id="place_id" name="place_id" class="form-control selectCat select2">
                                                    <?php if (!empty($places)) { ?>

                                                        <?php foreach ($places AS $place) { ?>

                                                            <option value="<?= $place['id_place'] ?>"><?= $place['name'] ?></option>


                                                        <?php } ?>
                                                    <?php } ?>

                                                </select>
                                            </div>

                                            <div class="col-lg-3">

                                            </div>

                                        </div>
                                       


                                    </div>
                                    <!-- textarea -->
                                    <div class="form-group">
                                        <label><?= Translate::sprint("Detail", "") ?> :</label>
                                        <textarea id="editable-textarea" class="form-control" style="height: 300px"></textarea>
                                    </div>
<!-- textarea -->
                                    <div class="form-group">
                                        <label><?= Translate::sprint("AR Detail", "") ?> :</label>
                                        <textarea id="editable-textarea_ar" class="form-control" style="height: 300px">Arabic Content</textarea>
                                    </div>


                                    <div class="form-group">
                                        <label><?= Translate::sprint("Phone Number", "") ?> :</label>
                                        <input type="text" class="form-control"
                                               placeholder="<?= Translate::sprint("Enter") ?> ..." name="tel" id="tel">
                                    </div>

                                </div>
                                <!-- /.box-body -->
                            </div>


                            <!-- text input -->


                            <?php

                            if(ModulesChecker::isRegistred("gallery")){
                                //load view
                                $this->mGalleryModel->loadHtml("upgallery");
                            }
                            ?>



                        </div>
                    </div>

                </div>
                <div class="col-md-6">


                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <b><?= Translate::sprint("Drag the marker to get the exact position", "") ?> :</b></h3>
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label> <?= Translate::sprint("Search", "") ?> :</label>
                                <input type="text" class="form-control"
                                       placeholder="<?= Translate::sprint("Search") ?> ..." name="places" id="places">
                            </div>
                            <div id="somecomponent" style="width:100%;height:500px;margin-bottom: 15px"></div>
                            <div class="form-group">
                                <label> <?= Translate::sprint("Address", "") ?> :</label>
                                <input type="text" class="form-control"
                                       placeholder="<?= Translate::sprint("Enter") ?> ..." name="address" id="address">
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label><?= Translate::sprint("Lat", "") ?> : </label> <input
                                            class="form-control" type="text" name="lat" id="lat" value="<?=$lat?>"/>
                                    </div>
                                    <div class="col-md-6 ">
                                        <label><?= Translate::sprint("Lng", "") ?> : </label> <input
                                            class="form-control" type="text" name="long" id="lng"  value="<?=$lng?>"/>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">

                                <button type="button" class="btn  btn-primary" id="btnCreate"><span
                                        class="glyphicon glyphicon-check"></span>
                                    <?= Translate::sprint("Create", "") ?> </button>
                                <button type="reset" class="btn  btn-default"><span
                                        class="glyphicon glyphicon-remove"></span>
                                    <?= Translate::sprint("Clear", "") ?></button>

                            </div>

                        </div>

                    </div>


                </div>
            </form>

    </section>

</div>


<script src="<?= base_url("views/skin/backend/plugins/jQuery/jQuery-2.1.4.min.js") ?>"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="<?= base_url("views/skin/backend/plugins/uploader/js/jquery.iframe-transport.js") ?>"></script>
<script src="<?= base_url("views/skin/backend/plugins/uploader/js/jquery.ui.widget.js") ?>"></script>
<script src="<?= base_url("views/skin/backend/plugins/uploader/js/jquery.fileupload.js") ?>"></script>

<script>

    <?php

    $token = $this->mUserBrowser->setToken("SUPIMAGES-4555");

    ?>


    Uploader();

    $('#fileuploadbtn').on('click', function() {
        $('#fileuploadinput').trigger('click');
    });

    var fileUploaded = {};
    function Uploader() {
        //fileuploadinput
        $('#fileuploadinput').fileupload({
            url: "<?=site_url("ajax/uploader/uploadImage")?>",
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

                $("#fileuploadinput").removeClass("input-error");
                $(".image-data").text("");

            }
        });


    }


</script>

<?php

if(ModulesChecker::isRegistred("gallery")){
    //load view
    $upfield = $this->mGalleryModel->loadJs("upgallery");
}
?>




<script src="<?= base_url("views/skin/backend/plugins/locationpicker/locationpicker.jquery.min.js") ?>"></script>
<script type="text/javascript"
        src='https://maps.googleapis.com/maps/api/js?key=<?= MAPS_API_KEY ?>&libraries=places'></script>
<script>


    $('#somecomponent').locationpicker({
        location: {latitude: <?=$lat?>, longitude:<?=$lng?>},
        radius: 300,
        inputBinding: {
            latitudeInput: $('#lat'),
            longitudeInput: $('#lng'),
            radiusInput: $('#radius'),
            locationNameInput: $('#places')
        },
        enableAutocomplete: true
    });


</script>


<script>


    $("#btnCreate").on('click', function () {

        var name = $("#form #name").val();
        var name_ar = $("#form #name_ar").val();
        var address = $("#form #address").val();
        var detail = $("#editable-textarea").val();
        var detail_ar = $("#editable-textarea_ar").val();
        var tel = $("#form #tel").val();
        var cat = $("#form #cat").val();

        var lat = $("#form #lat").val();
        var lng = $("#form #lng").val();
                var place_id = $("#form #place_id").val();

        
        $.ajax({
            url: "<?=  site_url("ajax/store/createStore")?>",
            data: {
                "name": name,
                "name_ar": name_ar,
                "address": address,
                "detail": detail,
                "detail_ar": detail_ar,
                "tel": tel,
                "cat": cat,
                "lat": lat,
                "lng": lng,
                "place_id":place_id,
                "images": JSON.stringify(fileUploaded),
                <?php if(ModulesChecker::isRegistred("gallery")){ ?>
                "gallery": JSON.stringify(<?=$upfield?>)
                <?php } ?>
            },
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {
                $("#btnCreate").attr("disabled", true);

            }, error: function (request, status, error) {
                alert(request.responseText);
                $("#btnCreate").attr("disabled", false);

                console.log(request);
            },
            success: function (data, textStatus, jqXHR) {

                $("#btnCreate").attr("disabled", false);
                if (data.success === 1) {
                    document.location.href = "<?=admin_url("store/stores")?>";

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


    $('.selectCat').select2();


</script>


