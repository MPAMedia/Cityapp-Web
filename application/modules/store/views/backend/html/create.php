<?php


$categories = $categories[Tags::RESULT];


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


                                        <?php

                                            $upload_plug = $this->uploader->plugin(array(
                                                "limit_key"     => "publishFiles",
                                                "token_key"     => "SzYjES-4555",
                                                "limit"         => MAX_STORE_IMAGES,
                                            ));

                                            echo $upload_plug['html'];
                                            TemplateManager::addScript($upload_plug['script']);

                                        ?>


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

                                    <!-- textarea -->
                                    <div class="form-group">
                                        <label><?= Translate::sprint("Detail", "") ?> :</label>
                                        <textarea id="editable-textarea" class="form-control" style="height: 300px"></textarea>
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
                                $gallery_variable = $this->mGalleryModel->setup("store-gallery");
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


                        </div>

                        <div class="box-footer">

                            <?php

                            $usr_id = $this->mUserBrowser->getData('id_user');
                            $nbr_stores = UserSettingSubscribe::getUDBSetting($usr_id,KS_NBR_STORES);


                            ?>

                            <?php if($nbr_stores>0 or $nbr_stores==-1): ?>
                                <button type="button" class="btn  btn-primary" id="btnCreate"><span
                                            class="glyphicon glyphicon-check"></span>
                                    <?= Translate::sprint("Create", "") ?> </button>
                                <button type="reset" class="btn  btn-default"><span
                                            class="glyphicon glyphicon-remove"></span>
                                    <?= Translate::sprint("Clear", "") ?></button>
                            <?php else: ?>
                                <button type="button" class="btn  btn-primary" id="btnCreate" disabled><span
                                            class="glyphicon glyphicon-check"></span>
                                    <?= Translate::sprint("Create", "") ?> </button>
                                &nbsp;&nbsp;
                                <span class="text-red font-size12px"><i class="mdi mdi-information-outline"></i>&nbsp;<?=Translate::sprint(Messages::EXCEEDED_MAX_NBR_STORES)?></span>
                               <?php endif; ?>
                        </div>

                    </div>


                </div>
            </form>

    </section>

</div>
<?php

    $data['lat'] = $lat;
    $data['lng'] = $lng;
    $data['gallery_variable'] = $gallery_variable;
    $data['uploader_variable'] = $upload_plug['var'];

    $script = $this->load->view('store/backend/html/scripts/create-script',$data,TRUE);
    TemplateManager::addScript($script);

?>