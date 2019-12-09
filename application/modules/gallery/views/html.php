<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title"><b><i class="mdi mdi-image-album"></i> <?= Translate::sprint("Store gallery") ?></b></h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">

        <div class="form-group required">


            <?php

                $upload_plug = $this->uploader->plugin(array(
                    "limit_key"     => "publishFiles",
                    "token_key"     => "SzYjES-4555",
                    "array_name"    => $variable,
                    "limit"         => MAX_GALLERY_IMAGES,
                    "cache"         => $gallery,
                ));

                echo $upload_plug['html'];
                TemplateManager::addScript($upload_plug['script']);

            ?>

        </div>


    </div>
    <!-- /.box-body -->
</div>


