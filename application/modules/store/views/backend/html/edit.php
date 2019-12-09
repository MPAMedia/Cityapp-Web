<?php
$categories = $categories[Tags::RESULT];
$places = $places[Tags::RESULT];

$store = $dataStores[Tags::RESULT][0];


$disabled = "";
if ($store['user_id'] != $this->mUserBrowser->getData("id_user") AND $this->mUserBrowser->getData("typeAuth") == "admin") {
    $disabled = "disabled='true'";
}


if ($store['user_id'] != $this->mUserBrowser->getData("id_user") AND $this->mUserBrowser->getData("typeAuth") != "admin") {
    redirect(admin_url("error404"));
}

?>
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

                                        <?php if ($store['user_id'] == $this->mUserBrowser->getData("id_user")) { ?>
                                        <label class="nsup-fileuploadlabel" for="nsup-photogallery">
                                            <span id="fileuploadbtn" class="nsup-btn"><strong><?=Translate::sprint("Select Photo")?></strong></span>
                                            <span><?=Translate::sprintf("Maximum upload file size: %s",array(MAX_IMAGE_UPLOAD." MB"))?></span>
                                            <input id="fileuploadinput" class="nsup-fileinput" type="file" name="addimage">

                                        </label>
                                        <?php } ?>

                                        <!-- text input -->

                                        <?php

                                        $images = $store['images'];
                                        if ($images != "" AND !is_array($images)) {
                                            $images = json_decode($images);
                                        }

                                        ?>

                                        <div class="form-group required">

                                            <label class="msg-error-form image-data"></label>


                                            <div class="clear"></div>


                                            <div id="progress" class="hidden">
                                                <div class="percent" style="width: 0%"></div>
                                            </div>


                                            <div class="clear"></div>

                                            <div id="image-previews">

                                                <?php if (!empty($images)) { ?>

                                                    <?php foreach ($images as $key => $value) { ?>

                                                        <?php

                                                        $name = $value['name'];
                                                        $item = "item_" . $name;

                                                        $imagesData = $value;

                                                        ?>


                                                        <div class="image-uploaded item_<?= $name ?>">
                                                            <a id="image-preview">
                                                                <img src="<?= $imagesData['200_200']['url'] ?>" alt="">
                                                            </a>

                                                            <div class="clear"></div>
                                                            <?php if ($store['user_id'] == $this->mUserBrowser->getData("id_user")) { ?>
                                                                <a href="#" data="<?= $name ?>" id="delete"><i
                                                                            class="fa fa-trash"></i>&nbsp;&nbsp;<?= Translate::sprint("Delete", "") ?>
                                                                </a>
                                                            <?php } ?>


                                                        </div>


                                                    <?php } ?>
                                                <?php } ?>
                                            </div>


                                        </div>




                                    </div>

                                </div>
                                <!-- /.box-body -->
                            </div>


                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><b><?= Translate::sprint("Update your store") ?></b></h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">



                                    <input type="hidden" id="id" value="<?= $store['id_store'] ?>">
                                    <div class="form-group">
                                        <label><?= Translate::sprint("Name", "") ?> : </label>
                                        <input <?= $disabled ?> type="text" class="form-control"
                                                                placeholder="<?= Translate::sprint("Enter") ?> ..."
                                                                value="<?= $store['name'] ?>" name="name" id="name">
                                    </div>
                                       <div class="form-group">
                                        <label><?= Translate::sprint("AR Name", "") ?> : </label>
                                        <input <?= $disabled ?> type="text" class="form-control"
                                                                placeholder="<?= Translate::sprint("Enter Aarabic Content") ?> ..."
                                                                value="<?= $store['name_ar'] ?>" name="name_ar" id="name_ar">
                                    </div>

                                    <div class="form-group">
                                        <label><?= Translate::sprint("Category", "") ?> :</label>
                                        <select id="cat" name="cat" class="form-control select2 selectCat" <?= $disabled ?> >
                                            <?php if (!empty($categories)) { ?>

                                                <?php foreach ($categories AS $cat) {
                                                    if ($cat['id_category'] == $store['category_id']) {
                                                        ?>

                                                        <option selected
                                                                value="<?= $cat['id_category'] ?>"><?= $cat['name'] ?></option>

                                                    <?php } else {
                                                        ?>
                                                        <option value="<?= $cat['id_category'] ?>"><?= $cat['name'] ?></option>


                                                    <?php }
                                                } ?>
                                            <?php } ?>

                                        </select>
                                    </div>
                                      <div class="form-group">
                                        <label><?= Translate::sprint("Place", "") ?> :</label>
                                        <select id="place_id" name="place_id" class="form-control select2 selectCat" <?= $disabled ?> >
                                            <?php if (!empty($places)) { ?>

                                                <?php foreach ($places AS $place_C) {
                                                    if ($place_C['id_place'] == $store["place_id"]) {
                                                        ?>

                                                        <option selected
                                                                value="<?= $place_C['id_place'] ?>"><?= $place_C['name'] ?></option>

                                                    <?php } else {
                                                        ?>
                                                        <option value="<?= $place_C['id_place'] ?>"><?= $place_C['name'] ?></option>


                                                    <?php }
                                                } ?>
                                            <?php } ?>

                                        </select>
                                    </div>

                                    <!-- textarea -->
                                    <div class="form-group">
                                        <label><?= Translate::sprint("Detail", "") ?> :</label>
                                        <textarea <?= $disabled ?> id="editable-textarea" class="form-control"
                                                                   style="height: 300px"><?= $store['detail'] ?></textarea>

                                    </div>
                                    <!-- textarea -->
                                    <div class="form-group">
                                        <label><?= Translate::sprint("AR Detail", "") ?> :</label>
                                        <textarea <?= $disabled ?> id="editable-textarea_ar" class="form-control"
                                                                   style="height: 300px"><?= $store['detail_ar'] ?></textarea>

                                    </div>


                                    <div class="form-group">
                                        <label><?= Translate::sprint("Phone Number", "") ?> :</label>
                                        <input <?= $disabled ?> type="text" class="form-control"
                                                                placeholder="<?= Translate::sprint("Enter") ?> ..."
                                                                value='<?= $store['telephone'] ?>' name="tel" id="tel">
                                    </div>

                                </div>
                                <!-- /.box-body -->
                            </div>


                            <?php

                            if(ModulesChecker::isRegistred("gallery")){
                                //load view
                                $this->mGalleryModel->loadHtml("upgallery",$gallery[Tags::RESULT],$store['user_id']);
                            }
                            ?>

                        </div>
                    </div>

                </div>




                <div class="col-md-6">




                    <?php if($this->mUserBrowser->getData("typeAuth")=="admin"): ?>

                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>
                                    <?=Translate::sprint("Options")?></b></h3>
                        </div>

                        <div class="box-body">

                            <?php

                            $checked0 = "";
                            if(intval($store['featured'])==0)
                                $checked0 = " checked='checked'";

                            $checked = "";
                            if(intval($store['featured'])==1)
                                $checked = " checked='checked'";

                            ?>

                            <div class="form-group">
                                <label style="cursor: pointer;">
                                    <input name="featured" type="radio" id="featured_item0" <?=$checked0?>>&nbsp;&nbsp;
                                    <?=Translate::sprint("Disabled Featured")?>
                                </label><br>
                                <label style="cursor: pointer;">
                                    <input name="featured" type="radio" id="featured_item1" <?=$checked?>>&nbsp;&nbsp;
                                    <?=Translate::sprint("Make it as featured")?>
                                </label>
                            </div>


                        </div>

                    </div>

                    <?php endif;?>

             </div>

              <div class="col-md-6">




                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>
                                    <?= Translate::sprint("Drag the marker to get the exact position", "") ?> :</b></h3>
                        </div>

                        <div class="box-body">




                            <div class="form-group">
                                <label> <?= Translate::sprint("Search", "") ?> :</label>
                                <input type="text" class="form-control"
                                       placeholder="<?= Translate::sprint("Search") ?> ..." name="places" id="places">
                            </div>
                            <div id="somecomponent" style="width:100%;height:500px;margin-bottom: 15px"></div>
                            <div class="form-group">
                                <label><?= Translate::sprint("Address", "") ?> :</label>
                                <input <?= $disabled ?> type="text" class="form-control"
                                                        placeholder="<?= Translate::sprint("Enter") ?> ..."
                                                        value="<?= $store['address'] ?>" name="address" id="address">
                            </div>
                            <div class="form-group">
                                <div class="row no-margin no-padding">
                                    <div class="col-md-6  no-padding"><label> <?= Translate::sprint("Lat", "") ?>
                                            : </label> <input <?= $disabled ?> class="form-control"
                                                                               value="<?= $store['latitude'] ?>"
                                                                               type="text" name="lat" id="lat"/></div>
                                    <div class="col-md-6  no-padding"><label><?= Translate::sprint("Lng", "") ?>
                                            : </label> <input <?= $disabled ?> class="form-control"
                                                                               value="<?= $store['longitude'] ?>"
                                                                               type="text" name="long" id="lng"/></div>
                                </div>
                            </div>

                            <?php if ($store['user_id'] == $this->mUserBrowser->getData("id_user")) { ?>
                                <div class="form-group">
                                    <button type="button" class="btn  btn-primary" id="btnCreate"><span
                                            class="glyphicon glyphicon-check"></span>
                                        <?= Translate::sprint("Update", "") ?> </button>
                                    <button type="reset" class="btn  btn-default"><span
                                            class="glyphicon glyphicon-remove"></span>
                                        <?= Translate::sprint("Clear", "") ?> </button>
                                </div>
                                <?php

                            } else {


                            }


                            ?>
                        </div>

                    </div>


                </div>
            </form>

    </section>

</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="<?= base_url("views/skin/backend/plugins/uploader/js/jquery.iframe-transport.js") ?>"></script>
<script src="<?= base_url("views/skin/backend/plugins/uploader/js/jquery.ui.widget.js") ?>"></script>
<script src="<?= base_url("views/skin/backend/plugins/uploader/js/jquery.fileupload.js") ?>"></script>

<?php if ($store['user_id'] == $this->mUserBrowser->getData("id_user")) { ?>

    <script>

        <?php

        $token = $this->mUserBrowser->setToken("SUPIMAGES-4555");

        ?>

        Uploader();

        var fileUploaded = {};

        <?php
        if (!empty($images)) {

            foreach ($images as $key => $value) {

                $name = $value['name'];
                $item = "item_" . $name;


                echo "fileUploaded[" . $name . "]=$name ;";

            }
        }
        ?>


        $(".image-uploaded #delete").on('click', function () {

            var nameDir = $(this).attr("data");

            delete fileUploaded[nameDir];

            console.log(fileUploaded);


            $(".image-uploaded.item_" + nameDir).remove();


            return false;
        });

        $('#fileuploadbtn').on('click', function() {
            $('#fileuploadinput').trigger('click');
        });

        function Uploader() {

            $('#fileuploadinput').fileupload({
                url: "<?=site_url("ajax/uploader/uploadImage")?>",
                sequentialUploads: true,
                formData: {
                    'token': "<?=$token?>",
                    'ID': "<?=sha1($token)?>"
                },
                dataType: 'json',
                done: function (e, data) {


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

                },
                progressall: function (e, data) {


                    var progress = parseInt(data.loaded / data.total * 100, 10);

                    $("#progress").removeClass("hidden");
                    $("#progress .percent").animate({"width": progress + "%"}, "linear");

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
        $upfield = $this->mGalleryModel->loadJs("upgallery",$gallery[Tags::RESULT],$store['user_id']);
    }
    ?>

<?php } ?>


<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="<?= base_url("views/skin/backend/plugins/locationpicker/locationpicker.jquery.min.js") ?>"></script>
<script type="text/javascript"
        src='https://maps.googleapis.com/maps/api/js?key=<?=MAPS_API_KEY?>&libraries=places'></script>
<script>
    $('#somecomponent').locationpicker({
        location: {
            latitude: <?=$store['latitude']?>, longitude:<?=$store['longitude']?>
        },
        radius: 300,
        inputBinding: {
            latitudeInput: $('#lat'),
            longitudeInput: $('#lng'),
            radiusInput: $('#radius'),
            locationNameInput: $('#places')
        }, enableAutocomplete: true
    });

    <?php if($store['user_id'] == $this->mUserBrowser->getData("id_user")) { ?>
    $("#btnCreate").on('click', function () {

        var selector = $(this);

        var id = $("#form #id").val();
        var name = $("#form #name").val();
        var name_ar = $("#form #name_ar").val();
        var address = $("#form #address").val();
        var detail = $("#editable-textarea").val();
        var detail_ar = $("#editable-textarea_ar").val();
        var tel = $("#form #tel").val();
        var cat = $("#form #cat").val();
        var place_id = $("#form #place_id").val();
        var lat = $("#form #lat").val();
        var lng = $("#form #lng").val();

      

        $.ajax({
            url: "<?=  site_url("ajax/store/edit")?>",
            data: {
                'id': id,
                "name": name,
                "name_ar": name_ar,
                "address": address,
                "detail": detail,
                "detail_ar": detail_ar,
                "tel": tel,
                "cat": cat,
                "place_id":place_id,
                "lat": lat,
                "lng": lng,
                
                "images": JSON.stringify(fileUploaded),
                <?php if(ModulesChecker::isRegistred("gallery")){ ?>
                "gallery": JSON.stringify(<?=$upfield?>)
                <?php } ?>
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


                selector.attr("disabled", false);
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
    <?php } ?>


</script>


<script src="<?=  base_url("views/skin/backend/plugins/select2/select2.full.min.js")?>"></script>
<script>
    $('.selectCat').select2();
</script>
<?php if($this->mUserBrowser->getData("typeAuth")=="admin"): ?>
<script>


    $("#featured_item1").change(function () {

        var featured = 0;

        if(this.checked)
            featured = 1;
        else
            featured = 0;

        //   alert(featured);

        $.ajax({
            url:"<?=  site_url("ajax/store/markAsFeatured")?>",
            data:{
                "id": "<?=$store['id_store']?>",
                "featured": featured,
                "type": "store"
            },
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {

            },
            error: function (request, status, error) {
                console.log(request);
            },
            success: function (data, textStatus, jqXHR) {

                if(data.success===1){

                    document.location.reload();

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
        return true;
    });


    $("#featured_item0").change(function () {

        var featured = 0;


        $.ajax({
            url:"<?=  site_url("ajax/store/markAsFeatured")?>",
            data:{
                "id": "<?=$store['id_store']?>",
                "featured": featured,
                "type": "store"
            },
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {

            },
            error: function (request, status, error) {
                console.log(request);
            },
            success: function (data, textStatus, jqXHR) {

                if(data.success===1){

                    document.location.reload();

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
        return true;
    });

</script>

<?php endif;?>





