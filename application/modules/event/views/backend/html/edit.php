<?php

$event = $dataEvents[Tags::RESULT][0];


if($event['user_id'] != $this->mUserBrowser->getData("id_user")
    && $this->mUserBrowser->getData("typeAuth")=="admin"){
    $disabled = "disabled='true'";
}else{
    $disabled = "";
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
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b><?=Translate::sprint("Edit event")?></b></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">

                            <!-- text input -->

                            <input type="hidden" id="id" value="<?= $event['id_event'] ?>">
                            <div class="form-group required">

                                <?php


                                $images = $event['images'];

                                ?>
                                <label for="name"><?= Translate::sprint("Images") ?>: </label>

                                <label class="msg-error-form image-data"></label>
                                <?php if ($event['user_id'] == $this->mUserBrowser->getData("id_user")) { ?>
                                    <input type="file" name="addimage" id="fileupload" <?=$disabled?>><br>
                                <?php } ?>

                                <div class="clear"></div>

                                <div id="progress" class="hidden">
                                    <div class="percent" style="width: 0%"></div>
                                </div>


                                <div class="clear"></div>


                                <div id="image-previews">

                                    <?php if (!empty($images)) { ?>

                                        <?php foreach ($images as $value) { ?>

                                            <?php

                                            $item = "item_" . $value['name'];
                                            $idata = $value['name'];
                                            //$imagesData = _openDir($value);
                                            $imagesData = $value;

                                            ?>


                                            <div class="image-uploaded <?= $item ?>">
                                                <a id="image-preview">
                                                    <img src="<?= $imagesData['200_200']['url'] ?>" alt="">
                                                </a>

                                                <div class="clear"></div>
                                                <a href="#" data="<?= $idata ?>" id="delete"><i
                                                        class="fa fa-trash"></i>&nbsp;&nbsp;
                                                    <?= Translate::sprint("Delete", "") ?></a></div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>


                            </div>


                            <div class="form-group">
                                <label><?= Translate::sprint("Store", "") ?></label>
                                <select class="form-control select2 selectStore" style="width: 100%;" <?=$disabled?>>
                                    <option value="0"><?= Translate::sprint("Select store", "") ?></option>
                                    <?php

                                    if (isset($myStores[Tags::RESULT])) {
                                        foreach ($myStores[Tags::RESULT] as $st) {

                                            if ($event['store_id'] != $st['id_store'])
                                                echo '<option adr="' . $st['address'] . '" 
                                        lat="' . $st['latitude'] . '" lng="' . $st['longitude'] . '" 
                                        value="' . $st['id_store'] . '">' . $st['name'] . '</option>';
                                            else {
                                                echo '<option selected adr="' . $st['address'] . '" 
                                        lat="' . $st['latitude'] . '" lng="' . $st['longitude'] . '" 
                                        value="' . $st['id_store'] . '">' . $st['name'] . '</option>';
                                            }
                                        }
                                    }

                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><?= Translate::sprint("Event name", "") ?> : </label>
                                <input <?=$disabled?> type="text" class="form-control" value="<?= $event['name'] ?>"
                                       placeholder="<?= Translate::sprint("Enter") ?> ..." name="name" id="name">

                            </div> 
                             <div class="form-group">
                                <label><?= Translate::sprint("Event AR name", "") ?> : </label>
                                <input <?=$disabled?> type="text" class="form-control" value="<?= $event['name_ar'] ?>"
                                       placeholder="<?= Translate::sprint("Enter AR Name") ?> ..." name="name_ar" id="name_ar">

                            </div>


                            <!-- textarea -->
                            <div class="form-group">
                                <label><?= Translate::sprint("Description", "") ?> :</label>
                                <textarea <?=$disabled?> id="editable-textarea" class="form-control" style="height: 300px"><?= $event['description'] ?></textarea>
                            </div>
   <!-- textarea -->
                            <div class="form-group">
                                <label><?= Translate::sprint("Description AR", "") ?> :</label>
                                <textarea <?=$disabled?> id="editable-textarea_ar" class="form-control" style="height: 300px"><?= $event['description_ar'] ?></textarea>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6"><label><?= Translate::sprint("Date Begin", "") ?> : </label>
                                        <input <?=$disabled?> class="form-control" data-provide="datepicker"
                                               value="<?php $dat_1 = date_create($event['date_b']);
                                               echo date_format($dat_1, 'd-m-Y') ?>" placeholder="DD-MM-YYYY"
                                               type="text" name="date_b" id="date_b"/></div>
                                    <div class="col-md-6"><label><?= Translate::sprint("Date End ", "") ?> : </label>
                                        <input <?=$disabled?> class="form-control" data-provide="datepicker"
                                               value="<?php $dat_2 = date_create($event['date_e']);
                                               echo date_format($dat_2, 'd-m-Y') ?>" type="text"
                                               placeholder="DD-MM-YYYY" name="date_e" id="date_e"/></div>
                                </div>
                            </div>


                            <div class="form-group">
                                <label><?= Translate::sprint("Phone Number", "") ?> :</label>
                                <input <?=$disabled?> type="text" class="form-control" value="<?= $event['tel'] ?>"
                                       placeholder="<?= Translate::sprint("Enter") ?> ..." name="tel" id="tel">
                            </div>


                            <div class="form-group">
                                <label><?= Translate::sprint("WebSite", "") ?> :</label>
                                <input <?=$disabled?> type="text" class="form-control" value="<?= $event['website'] ?>"
                                       placeholder="<?= Translate::sprint("Enter") ?> ..." name="web" id="web">
                            </div>


                        </div>
                        <!-- /.box-body -->
                    </div>


                </div>

                <?php if($this->mUserBrowser->getData("typeAuth")=="admin"): ?>
                    <div class="col-md-6">

                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title"><b>
                                        <?=Translate::sprint("Options")?></b></h3>
                            </div>

                            <div class="box-body">

                                <?php

                                $checked0 = "";
                                if(intval($event['featured'])==0)
                                    $checked0 = " checked='checked'";

                                $checked = "";
                                if(intval($event['featured'])==1)
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

                    </div>
                <?php endif;?>
                <div class="col-md-6">


                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <b><?= Translate::sprint("Drag the marker to get the exact position", "") ?> :</b></h3>
                        </div>

                        <div class="box-body">

                            <div class="form-group">
                                <label> <?= Translate::sprint("Search", "") ?> :</label>
                                <input <?=$disabled?> type="text" class="form-control"
                                       placeholder="<?= Translate::sprint("Search") ?> ..." name="places" id="places">
                            </div>
                            <div id="somecomponent" style="width:100%;height:500px;margin-bottom: 10px;"></div>

                            <div class="form-group">
                                <label><?= Translate::sprint("Address") ?> :</label>
                                <input <?=$disabled?> type="text" class="form-control" value="<?= trim($event['address']) ?>"
                                       placeholder="<?= Translate::sprint("Enter") ?> ..." name="address" id="address">
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6"><label><?= Translate::sprint("Lat", "") ?> : </label> <input
                                            <?=$disabled?> class="form-control" value="<?= $event['lat'] ?>" type="text" name="lat"
                                            id="lat"/></div>
                                    <div class="col-md-6"><label><?= Translate::sprint("Lng", "") ?> : </label> <input
                                            <?=$disabled?> class="form-control" value="<?= $event['lng'] ?>" type="text" name="long"
                                            id="lng"/></div>

                                </div>
                            </div>

                            <?php if ($event['user_id'] == $this->mUserBrowser->getData("id_user")) { ?>
                                <div class="form-group">
                                    <button type="button" class="btn  btn-primary" id="btnCreate"><span
                                            class="glyphicon glyphicon-check"></span>
                                        <?= Translate::sprint("update", "") ?> </button>
                                    <button type="reset" class="btn  btn-default"><span
                                            class="glyphicon glyphicon-remove"></span>
                                        <?= Translate::sprint("Clear", "") ?> </button>
                                </div>
                            <?php } ?>

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
<script src="<?= base_url("views/skin/backend/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>

<?php if ($event['user_id'] == $this->mUserBrowser->getData("id_user")) { ?>
    <script>


        $.fn.datepicker.defaults.format = "dd-mm-yyyy";
        $('.datepicker').datepicker({
            startDate: '-3d'
        });
        <?php

        $token = $this->mUserBrowser->setToken("SUPIMAGES-4555");

        ?>

        Uploader(true);

        var fileUploaded = {};

        <?php
        if (!empty($images)) {


            foreach ($images as $value) {

                $item = "item_" . $value['name'];
                $data = $value;

                echo "fileUploaded[".$value['name']."]=".$value['name']." ;";

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


        function Uploader(singleFile){

            $('#fileupload').fileupload({
                url: "<?=site_url("uploader/ajax/uploadImage")?>",
                sequentialUploads: true,
                loadImageFileTypes:/^image\/(gif|jpeg|png|jpg)$/,
                loadImageMaxFileSize: 10000,
                singleFileUploads: singleFile,

                formData     : {
                    'token'     : "<?=$token?>",
                    'ID'        : "<?=sha1($token)?>"
                },
                dataType: 'json',
                done: function (e, data) {


                    var results = data._response.result.results;
                    $("#progress").addClass("hidden");
                    $("#progress .percent").animate({"width":"0%"});
                    $(".image-uploaded").removeClass("hidden");

                    if(singleFile==true){
                        fileUploaded = {};
                        $("#image-previews").html(results.html);
                    }else
                        $("#image-previews").append(results.html);

                    fileUploaded[results.image] = results.image;
                    //$("#image-data").val(results.image_data);

                    $(".image-uploaded #delete").on('click',function(){
                        var nameDir = $(this).attr("data");
                        delete fileUploaded[nameDir];
                        $(".image-uploaded.item_"+nameDir).remove();
                        return false;
                    });

                },
                fail:function (e, data) {

                    $("#progress").addClass("hidden");
                    $("#progress .percent").animate({"width":"0%"});


                },
                progressall: function (e, data) {

                    var progress = parseInt(data.loaded / data.total * 100, 10);

                    $("#progress").removeClass("hidden");
                    $("#progress .percent").animate({"width":progress+"%"},"linear");

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
<?php } ?>

<script src="<?= base_url("views/skin/backend/plugins/jQuery/jQuery-2.1.4.min.js") ?>"></script>
<script src="<?= base_url("views/skin/backend/plugins/locationpicker/locationpicker.jquery.min.js") ?>"></script>
<!--    <script type="text/javascript" src='http://maps.google.com/maps/api/js?sensor=false&libraries=places'></script>
    <script type="text/javascript" src='https://maps.googleapis.com/maps/api/place/nearbysearch/json?key=AIzaSyAlGtU4pi1JQentEWQocVA7ynWtdNLfyd0'></script>-->
<script type="text/javascript"
        src='https://maps.googleapis.com/maps/api/js?key=<?=MAPS_API_KEY?>&libraries=places'></script>
<script>
    $('#somecomponent').locationpicker({
        location: {latitude: <?=$event['lat']?>, longitude:<?=$event['lng']?>},
        radius: 300,
        inputBinding: {
            latitudeInput: $('#lat'),
            longitudeInput: $('#lng'),
            radiusInput: $('#radius'),
            locationNameInput: $('#places')
        },
        enableAutocomplete: true
    });

    var store_id = "<?php if (isset($event['store_id']) AND !empty($event['store_id']))
        echo $event['store_id'];
        else echo -1;  ?>";


    <?php if($event['user_id'] == $this->mUserBrowser->getData("id_user")) { ?>

    $("#btnCreate").on('click', function () {

        var id = $("#form #id").val();
        var name = $("#form #name").val();
        var name_ar = $("#form #name_ar").val();
        var address = $("#form #address").val();
        var desc = $("#editable-textarea").val();
        var desc_ar = $("#editable-textarea_ar").val();
        var tel = $("#form #tel").val();
        var website = $("#form #web").val();

        var lat = $("#form #lat").val();
        var lng = $("#form #lng").val();

        var date_b = $("#form #date_b").val();
        var date_e = $("#form #date_e").val();


        $.ajax({
            url: "<?=  site_url("ajax/event/edit")?>",
            data: {
                "store_id": store_id,
                'id': id,
                "name": name,
                "name_ar": name_ar,
                "address": address,
                "desc": desc,
                "desc_ar": desc_ar,
                "tel": tel,
                "website": website,
                "lat": lat,
                "lng": lng,
                "date_b": date_b,
                "date_e": date_e,
                "images": JSON.stringify(fileUploaded)
            },
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {
                $("#btnCreate").attr("disabled", true);

            }, error: function (request, status, error) {
                alert(request.responseText);
                $("#btnCreate").attr("disabled", false);
            },
            success: function (data, textStatus, jqXHR) {


                $("#btnCreate").attr("disabled", false);
                if (data.success === 1) {
                    document.location.href = data.url;

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

<script src="<?= base_url("views/skin/backend/plugins/select2/select2.full.min.js") ?>"></script>
<script>

    $('.selectStore').select2();
    $('.selectStore').on('select2:select', function (e) {
        // Do something
        var data = e.params.data;
        var id = data.id;
        store_id = id;
    });
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
                url:"<?=  site_url("ajax/event/markAsFeatured")?>",
                data:{
                    "id": "<?=$event['id_event']?>",
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
                url:"<?=  site_url("ajax/event/markAsFeatured")?>",
                data:{
                    "id": "<?=$event['id_event']?>",
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