<?php

    $tag_id = "";

    if (isset($tag)) {
        $tag_id = "-" . $tag;
    }

?>

<div id="dt_uploader">

    <label class="nsup-fileuploadlabel nsup-file-upload<?= $tag_id ?>" for="nsup-photogallery">
         <span id="fileuploadbtn<?= $tag_id ?>" class="nsup-btn cursor-pointer"><strong><?= Translate::sprint("Select Images") ?></strong></span>
        <span class="desc"><?= Translate::sprintf("Maximum upload file size is %s", array(MAX_IMAGE_UPLOAD." MB")) ?></span>
        <span class="desc"><?= Translate::sprintf("The maximum number of files allowed is %s", array($limit)) ?></span>
        <span class="desc images-counter<?=$tag_id?>"><?=Translate::sprint("Uploaded files")?>: (<span class="count-<?= $tag_id ?>">0</span>/<?=$limit?>)</span>
        <input id="fileuploadinput<?= $tag_id ?>" class="nsup-fileinput" type="file"
               name="addimage">
    </label>
    <div class="clearfix"></div>
    <div id="progress<?= $tag_id ?>" class="progress hidden">
        <div class="percent" style="width: 0%"></div>
    </div>
    <?php if (!isset($cache)): ?>
        <div id="image-previews<?=$tag_id?>" class="image-previews hidden">

        </div>
    <?php else: ?>
        <div id="image-previews<?= $tag_id ?>" class="image-previews">

            <?php if (!empty($cache)) { ?>

                <?php foreach ($cache as $key => $value) { ?>

                    <?php


                    if(!isset($value['name']))
                        continue;

                    $name = $value['name'];
                    $item = "item_" . $name;

                    $imagesData = $value;

                    ?>

                    <div data-id="<?= $name ?>" class="image-uploaded cursor-draggable image-uploaded<?= $tag_id ?>  item_<?= $name ?>">
                        <a id="image-preview">
                            <img src="<?= $imagesData['200_200']['url'] ?>" alt="">
                        </a>
                        <div class="clear"></div>
                        <?php if(isset($undeletable) and $undeletable==TRUE): ?>
                            <a id="undeletable"><i
                                        class="fa fa-trash"></i>&nbsp;&nbsp;<?= Translate::sprint("Delete", "") ?>
                            </a>
                        <?php else: ?>
                            <a href="#" data="<?= $name ?>" id="delete"><i
                                        class="fa fa-trash"></i>&nbsp;&nbsp;<?= Translate::sprint("Delete", "") ?>
                            </a>
                        <?php endif; ?>

                    </div>


                <?php } ?>
            <?php } ?>
        </div>
    <?php endif; ?>


</div>


