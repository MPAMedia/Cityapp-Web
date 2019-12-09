<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title"><b><i class="mdi mdi-image-album"></i> <?= Translate::sprint("Store gallery") ?></b></h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">

        <div class="form-group required">


            <label class="nsup-fileuploadlabel" for="nsup-photogallery">
                <span id="fileuploadbtn-<?=$tag?>" class="nsup-btn"><strong><?=Translate::sprint("Select Photo")?></strong></span>
                <span><?=Translate::sprintf("Maximum upload file size: %s",array(MAX_IMAGE_UPLOAD." MB"))?></span>
                <span><?=Translate::sprintf("Maximum files is: %s photos",array($this->mGalleryModel->maxfiles))?></span>
                <input style="display: none" id="fileuploadinput-<?=$tag?>" class="nsup-fileinput" type="file" name="addimage">
            </label>

            <label class="msg-error-form image-data-<?=$tag?>"></label>
            <div class="clear"></div>
            <div id="progress-<?=$tag?>" class="hidden progress">
                <div class="percent" style="width: 0%"></div>
            </div>
            <div class="clear"></div>


            <div id="image-previews-<?=$tag?>">



                <?php if (!empty($images)) { ?>

                    <?php foreach ($images as $key => $value) { ?>

                        <?php

                        $name = $value['name'];
                        $item = "item_" . $name;

                        $imagesData = $value;

                        ?>


                        <div class="image-uploaded image-uploaded-tag-<?=$tag?> item_<?= $name ?>">
                            <a id="image-preview">
                                <img src="<?= $imagesData['200_200']['url'] ?>" alt="">
                            </a>

                            <div class="clear"></div>
                            <?php if ($uid>0 and $uid == $this->mUserBrowser->getData("id_user")) { ?>
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
    <!-- /.box-body -->
</div>


