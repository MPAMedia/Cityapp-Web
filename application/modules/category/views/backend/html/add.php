
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">


    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- Message Error -->
            <div class="col-sm-12">
                <?php $this->load->view("backend/include/messages");?>
            </div>

        </div>

        <div class="row">

            <?php if(GroupAccess::isGranted('category',ADD_CATEGORY)): ?>
            <div class="col-sm-6">
                <div class="box box-solid">
                    <div class="box-header">
                        <div class="box-title"><b><?=Translate::sprint("Add new category")?></b></div>
                    </div>

                    <div class="box-body">

                        <div class="form-group required">

                            <?php

                            $upload_plug = $this->uploader->plugin(array(
                                "limit_key"     => "aCFiles",
                                "token_key"     => "SzZaYjEsS-4555",
                                "limit"         => 1,
                            ));

                            echo $upload_plug['html'];
                            TemplateManager::addScript($upload_plug['script']);

                            ?>

                        </div>


                        <div class="form-group">
                            <label><?=Translate::sprint("Category name")?> <sup>*</sup> </label>
                            <input class="form-control"  id="name" type="text" placeholder="<?=Translate::sprint("Enter")?> ..."/>
                        </div>


                    </div>
                    <div class="box-footer">
                        <button type="submit" id="btnAdd" class="btn btn-primary btn-flat"><?=Translate::sprint("Add")?></button>
                    </div>
                </div>


            </div>
            <?php endif; ?>

            <?php $this->load->view("backend/html/list");?>


        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php if(GroupAccess::isGranted('category',ADD_CATEGORY)): ?>

    <?php

        $data['uploader_variable'] = $upload_plug['var'];

        $script = $this->load->view('backend/html/scripts/add-script',$data,TRUE);
        TemplateManager::addScript($script);

    ?>


<?php endif; ?>