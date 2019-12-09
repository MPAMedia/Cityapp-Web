<?php


$offerToEdit = $offerToEdit[Tags::RESULT][0];
$content = json_decode($offerToEdit['content'], JSON_OBJECT_AS_ARRAY);
$content_ar = json_decode($offerToEdit['content_ar'], JSON_OBJECT_AS_ARRAY);


$adminAccess = "";
if ($offerToEdit['user_id'] != $this->mUserBrowser->getData("id_user") && $this->mUserBrowser->getData("typeAuth") == "admin") {
    $adminAccess = "disabled";
}


?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- Message Error -->
            <div class="col-sm-12">
                <?php $this->load->view("backend/include/messages"); ?>
            </div>

        </div>

        <div class="row">


            <div class="col-xs-12">
                <form id="form">
                    <div class="box box-solid">
                        <div class="box-header">

                            <div class="box-title">
                                <b><?= Translate::sprint("Edit offer", "") ?></b>
                            </div>

                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">

                            <?php
                            $timeOffer = strtotime($offerToEdit['date_end']);
                            $currentTime = time();
                            ?>


                            <?php if ($timeOffer < $currentTime and ($offerToEdit['date_end'] != $offerToEdit['date_start'])) { ?>
                                <div class="callout callout-danger">
                                    <h4><?= Translate::sprint("Offer is expired") ?></h4>
                                </div>
                            <?php } ?>


                            <div class="col-sm-4">

                                <div class="form-group">
                                    <label><?= Translate::sprint("Store", "") ?></label>
                                    <select <?= $adminAccess ?> class="form-control select2 selectStore"
                                                                style="width: 100%;">
                                        <option selected="selected"
                                                value="0"><?= Translate::sprint("Select store", "") ?></option>
                                        <?php

                                        if (isset($myStores[Tags::RESULT])) {
                                            foreach ($myStores[Tags::RESULT] as $st) {
                                                if ($st['id_store'] == $offerToEdit['store_id']) {
                                                    echo '<option adr="' . $st['address'] . '" 
                                                    lat="' . $st['latitude'] . '" lng="' . $st['longitude'] . '" 
                                                    value="' . $st['id_store'] . '" selected>' . $st['name'] . '</option>';
                                                } else {
                                                    echo '<option adr="' . $st['address'] . '" 
                                                    lat="' . $st['latitude'] . '" lng="' . $st['longitude'] . '" 
                                                    value="' . $st['id_store'] . '">' . $st['name'] . '</option>';
                                                }

                                            }
                                        }

                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label><?= Translate::sprint("Name", "") ?></label>
                                    <input <?= $adminAccess ?> type="text" class="form-control" id="name"
                                                               placeholder="black friday offer"
                                                               value="<?= $offerToEdit['name'] ?>">
                                </div>
                                <div class="form-group">
                                    <label><?= Translate::sprint("الاسم", "") ?></label>
                                    <input <?= $adminAccess ?> type="text" class="form-control" id="name_ar"
                                                               placeholder="black friday offer"
                                                               value="<?= $offerToEdit['name_ar'] ?>">
                                </div>
                            </div>

                            <?php

                            $price = $content['price'];
                            $percent = $content['percent'];


                            if (!is_array($content['currency']))
                                $currency = json_decode($content['currency'], JSON_OBJECT_AS_ARRAY);
                            else
                                $currency = $content['currency'];

                            ?>

                            <div class="col-sm-4">

                                <div class="form-group">
                                    <div class="row">

                                        <div class="col-sm-6  no-margin">

                                            <label><?= Translate::sprint("Offer price") ?></label>
                                            <div class="input-group">
                                                    <span class="input-group-addon">
                                                      <input <?= $adminAccess ?> name="poffer" type="radio"
                                                                                 id="price" <?php if ($price > 0) echo "checked"; ?>>
                                                    </span>
                                                <input <?= $adminAccess ?> type="number" class="form-control"
                                                                           id="priceInput"
                                                                           placeholder="<?= Translate::sprint("Enter price of your offer") ?>"
                                                                           value="<?= $price ?>" <?php if ($price > 0 || $price < 0) echo "checked"; ?>>
                                            </div>
                                        </div>

                                        <div class="col-sm-6" style="padding-left: 0px;">
                                            <?php

                                            $currencies = $currencies = json_decode(CURRENCIES, JSON_OBJECT_AS_ARRAY);
                                            $default_currency = $this->mOfferModel->getDefaultCurrency();

                                            ?>
                                            <div class="form-group">
                                                <label><?= Translate::sprint("Select offer currency") ?></label>
                                                <select <?= $adminAccess ?> id="selectCurrency"
                                                                            class="form-control select2 selectCurrency"
                                                                            style="width: 100%;">
                                                    <option selected="selected"
                                                            value="0"> <?= Translate::sprint("Select") ?></option>
                                                    <?php
                                                    $def_currency = $this->mOfferModel->getDefaultCurrency();
                                                    foreach ($currencies as $key => $value) {

                                                        if ($key == $currency['code'])
                                                            echo '<option selected="selected" value="' . $key . '">' . $value['name'] . ' (' . $value['code'] . ')</option>';
                                                        else
                                                            echo '<option value="' . $key . '">' . $value['name'] . ' (' . $value['code'] . ')</option>';

                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label><?= Translate::sprint("Offer percent", "") ?> </label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                        <input <?= $adminAccess ?> name="poffer" type="radio"
                                                                                   id="percent" <?php if ($percent > 0 || $percent < 0) echo "checked"; ?>>
                                                </span>
                                                <input <?= $adminAccess ?> type="number" class="form-control"
                                                                           id="percentInput"
                                                                           <?php if ($percent == 0) echo "disabled"; ?>value="<?php if ($percent > 0 || $percent < 0) echo $percent; ?>"
                                                                           placeholder="Exemple : -50 %">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label> <?= Translate::sprint("Date Begin") ?>  </label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="mdi mdi-calendar"></i>
                                                        </div>
                                                        <input disabled class="form-control" data-provide="datepicker"
                                                               placeholder="YYYY-MM-DD" type="text" name="date_b"
                                                               id="date_b"
                                                               value="<?= date("Y-m-d", strtotime($offerToEdit['date_start'])) ?>"/>
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <label><?= Translate::sprint("Date End") ?> </label>

                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="mdi mdi-calendar"></i>
                                                        </div>

                                                        <?php

                                                        $date_end = "";
                                                        if ($offerToEdit['date_end'] != "")
                                                            $date_end = date("Y-m-d", strtotime($offerToEdit['date_end']));

                                                        ?>
                                                        <input <?= $adminAccess ?> class="form-control"
                                                                                   data-provide="datepicker" type="text"
                                                                                   placeholder="YYYY-MM-DD"
                                                                                   name="date_e" id="date_e"
                                                                                   value="<?= $date_end ?>"/>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-4">
                                <div class="form-group required">

                                    <?php

                                    $images = $offerToEdit['image'];

                                    if (!empty($images))
                                        $images = array($images);

                                    ?>

                                    <label for="name"><i
                                            class="mdi mdi-paperclip"></i>&nbsp;&nbsp;<?= Translate::sprint("Image", "") ?>
                                        <sup>*</sup></label>
                                    <label class="msg-error-form image-data"></label>
                                    <input <?= $adminAccess ?> type="file" name="addimage" id="fileupload"><br>
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
                            </div>


                            <div class="col-sm-10">
                                <div class="form-group">
                                    <label><?= Translate::sprint("Description", "") ?></label>
                                    <textarea <?= $adminAccess ?> class="form-control" rows="7" id="editable-textarea"
                                                                  placeholder="<?= Translate::sprint("Enter") ?> ..."><?= $content['description'] ?></textarea>
                                </div>
                                  <div class="form-group">
                                    <label><?=Translate::sprint("الوصف","")?></label>
                                    <textarea <?= $adminAccess ?> class="form-control" rows="7"  id="editable-textarea_ar"
                                              placeholder="<?=Translate::sprint("أدخل")?> ...">
                                                  <?= $content_ar['description'] ?>
                                              </textarea>
                                </div>
                            </div>


                            <?php if($this->mUserBrowser->getData("typeAuth")=="admin"): ?>
                                <?php

                                $checked0 = "";
                                if(intval($offerToEdit['featured'])==0)
                                    $checked0 = " checked='checked'";

                                $checked = "";
                                if(intval($offerToEdit['featured'])==1)
                                    $checked = " checked='checked'";

                                ?>
                                <div class="col-sm-10">
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
                            <?php endif;?>


                            <?php if ($adminAccess == "") { ?>

                                <div class="form-group col-sm-12">
                                    <button type="button" class="btn  btn-primary" id="btnEdit"><span
                                            class="glyphicon glyphicon-check"></span>
                                        <?= Translate::sprint("Edit", "") ?> </button>
                                    <button type="button" class="btn  btn-default" id="btnEdit"
                                            onclick="redirectToAddNew()"><span class="mdi mdi-sale "></span>
                                        <?= Translate::sprint("Create new", "") ?> </button>
                                </div>

                            <?php } ?>


                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </form>
            </div>


            <?php

            //            $data['list'] = $list;
            //            $data['pagination'] = $pagination;
            //            $this->load->view("backend/offers/offers",$data);

            ?>


            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>


<!-- DataTables -->
<script src="<?= base_url("views/skin/backend/plugins/datatables/jquery.dataTables.min.js") ?>"></script>
<script src="<?= base_url("views/skin/backend/plugins/datatables/dataTables.bootstrap.min.js") ?>"></script>
<!-- SlimScroll -->
<script src="<?= base_url("views/skin/backend/plugins/slimScroll/jquery.slimscroll.min.js") ?>"></script>
<!-- FastClick -->
<script src="<?= base_url("views/skin/backend/plugins/fastclick/fastclick.js") ?>"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="<?= base_url("views/skin/backend/plugins/uploader/js/jquery.iframe-transport.js") ?>"></script>
<script src="<?= base_url("views/skin/backend/plugins/uploader/js/jquery.ui.widget.js") ?>"></script>
<script src="<?= base_url("views/skin/backend/plugins/uploader/js/jquery.fileupload.js") ?>"></script>

<script>

    <?php

    $token = $this->mUserBrowser->setToken("SUPIMAGES-4555");

    ?>

    Uploader(true);

    var fileUploaded = {};
    <?php
    if (!empty($images)) {

        foreach ($images as $value) {

            $key = $value['name'];

            $item = "item_" . $key;
            $data = $value;

            echo "fileUploaded[$key]=$key ;";

        }
    }
    ?>


    $(".image-uploaded #delete").on('click', function () {
        var nameDir = $(this).attr("data");
        delete fileUploaded[nameDir];
        $(".image-uploaded.item_" + nameDir).remove();
        return false;
    });


    function Uploader(singleFile) {

        $('#fileupload').fileupload({
            url: "<?=site_url("uploader/ajax/uploadImage")?>",
            sequentialUploads: true,
            loadImageFileTypes: /^image\/(gif|jpeg|png|jpg)$/,
            loadImageMaxFileSize: 10000,
            singleFileUploads: singleFile,

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

                if (singleFile == true) {
                    fileUploaded = {};
                    $("#image-previews").html(results.html);
                } else
                    $("#image-previews").append(results.html);

                fileUploaded[results.image] = results.image;
                //$("#image-data").val(results.image_data);

                $(".image-uploaded #delete").on('click', function () {
                    var nameDir = $(this).attr("data");
                    delete fileUploaded[nameDir];
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

                $("#fileupload").removeClass("input-error");
                $(".image-data").text("");

            }
        });


    }


</script>

<!-- page script -->
<script src="<?= base_url("views/skin/backend/plugins/jQuery/jQuery-2.1.4.min.js") ?>"></script>
<script src="<?= base_url("views/skin/backend/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>
<script src="<?= base_url("views/skin/backend/plugins/select2/select2.full.min.js") ?>"></script>

<?php if ($adminAccess == "") { ?>
    <script>

        $('.selectCurrency').select2();

        var store_id = <?=$offerToEdit['store_id']?>;

        $.fn.datepicker.defaults.format = "yyyy-mm-dd";
        $('.datepicker').datepicker({
            startDate: '-3d'
        });

        <?php
        $token = $this->mUserBrowser->setToken("SU774aQ55");
        ?>

        $("#btnEdit").on('click', function () {

            var selectore = $(this);

            var description = $("#form #editable-textarea").val();
            var description_ar = $("#form #editable-textarea_ar").val();
            var name = $("#form #name").val();
            var name_ar = $("#form #name_ar").val();
            var price = 0;
            var percent = 0;

            if ($("#form #price").prop('disabled', !this.checked) && $("#form #priceInput").val().length != 0) {
                price = $("#form #priceInput").val();
            }

            if ($("#form #percent").prop('disabled', !this.checked) && $("#form #percentInput").val().length != 0) {
                percent = $("#form #percentInput").val();
            }

            var date_e = $("#form #date_e").val();

            var currency = $("#form #selectCurrency").val();

            var dataSet0 = {
                "token": "<?=$token?>",
                "store_id": store_id,
                "name": name,
                "name_ar": name_ar,
                "image": fileUploaded,
                "description": description,
                "description_ar": description_ar,
                "price": price,
                "date_end": date_e,
                "offer_id":<?=$offerToEdit['id_offer']?>,
                "percent": percent,
                "currency": currency
            };

            $.ajax({
                url: "<?=  site_url("ajax/offer/edit")?>",
                data: dataSet0,
                dataType: 'json',
                type: 'POST',
                beforeSend: function (xhr) {

                    selectore.attr("disabled", true);

                }, error: function (request, status, error) {
                    alert(request.responseText);
                    selectore.attr("disabled", false);
                    console.log(request)
                },
                success: function (data, textStatus, jqXHR) {

                    selectore.attr("disabled", false);
                    if (data.success === 1) {
                        document.location.href = "<?=admin_url("offer/offers")?>";
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


        $('.selectStore').select2();
        $('.selectStore').on('select2:select', function (e) {
            // Do something
            var data = e.params.data;
            var id = data.id;
            store_id = id;

            if (id > 0) {
                store_id = id;
            } else {
                store_id = 0;
            }

        });


        $("input[name=poffer]").on('change', function () {

            var checked = $(this).attr("id");
            if (checked == "price") {
                $("#" + checked + "Input").attr("disabled", false);
                $("#percentInput").attr("disabled", true);
                $("#selectCurrency").attr("disabled", false);
            } else {
                $("#" + checked + "Input").attr("disabled", false);
                $("#priceInput").attr("disabled", true);
                $("#selectCurrency").attr("disabled", true);
            }

        });


        <?php

        if ($price > 0) {

            echo '$("#priceInput").attr("disabled",false);
            $("#percentInput").attr("disabled",true);
            $("#selectCurrency").attr("disabled",false);';
        } else {
            echo '$("#percentInput").attr("disabled",false);
            $("#priceInput").attr("disabled",true);
            $("#selectCurrency").attr("disabled",true);';
        }

        ?>

        function redirectToAddNew() {
            document.location.href = "<?=admin_url("offer/add")?>";
        }

    </script>
<?php } ?>



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
                url:"<?=  site_url("ajax/offer/markAsFeatured")?>",
                data:{
                    "id": "<?=$offerToEdit['id_offer']?>",
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
                url:"<?=  site_url("ajax/offer/markAsFeatured")?>",
                data:{
                    "id": "<?=$offerToEdit['id_offer']?>",
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




