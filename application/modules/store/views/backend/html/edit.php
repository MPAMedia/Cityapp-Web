<?php
$categories = $categories[Tags::RESULT];

$store = $dataStores[Tags::RESULT][0];


$disabled = "";
if ($store['user_id'] != $this->mUserBrowser->getData("id_user")) {
    $disabled = "disabled='true'";
}

/*
if ($store['user_id'] != $this->mUserBrowser->getData("id_user") AND $this->mUserBrowser->getData("typeAuth") != "admin") {
    redirect(admin_url("error404"));
}
*/

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


                                        <!-- text input -->

                                        <?php

                                        $images = $store['images'];
                                        if ($images != "" AND !is_array($images)) {
                                            $images = json_decode($images);
                                        }

                                        ?>

                                        <div class="form-group required">

                                            <?php

                                                $upload_plug = $this->uploader->plugin(array(
                                                    "limit_key"     => "editFiles",
                                                    "token_key"     => "SzYjESA-4555",
                                                    "limit"         => MAX_STORE_IMAGES,
                                                    "cache"         => $images
                                                ));

                                                echo $upload_plug['html'];
                                                TemplateManager::addScript($upload_plug['script']);

                                            ?>

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


                                    <!-- textarea -->
                                    <div class="form-group">
                                        <label><?= Translate::sprint("Detail", "") ?> :</label>
                                        <textarea <?= $disabled ?> id="editable-textarea" class="form-control"
                                                                   style="height: 300px"><?= $store['detail'] ?></textarea>

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
                                $gallery_variable = $this->mGalleryModel->setup("store-gallery",$gallery[Tags::RESULT],$store['user_id']);
                            }
                            ?>


                        </div>
                    </div>

                </div>




                <div class="col-md-6">




                    <?php if(GroupAccess::isGranted('user',USER_ADMIN)): ?>

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

                    <?php endif; ?>
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

<?php


$data['store'] = $store;
$data['gallery_variable'] = $gallery_variable;
$data['uploader_variable'] = $upload_plug['var'];

$script = $this->load->view('backend/html/scripts/edit-script',$data,TRUE);
TemplateManager::addScript($script);

?>




