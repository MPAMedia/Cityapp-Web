<?php

$permission = json_decode($group_access["permissions"],JSON_OBJECT_AS_ARRAY);

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
            <div class="col-sm-12">

                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b> <?= Translate::sprint("Edit", "") ?>  </b></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form class="items" id="form" role="form">


                            <div class="col-sm-12">
                                <table class="table table-responsive">
                                    <tr>
                                        <th><?=Translate::sprint("Module Name")?></th>
                                        <th><?=Translate::sprint("Options")?></th>
                                    </tr>
                                    <?php foreach ($actions as $key => $action): ?>
                                    <tr class="item_<?=$key?>">
                                        <td>
                                            <label id="module_action_<?=$key?>" data-key="<?=$key?>">
                                                <input type="checkbox" class="minimal">
                                                &nbsp;&nbsp;<strong><?=Translate::sprint($key)?></strong>
                                                &nbsp;&nbsp;
                                            </label>
                                        </td>
                                        <td>
                                            <?php foreach ($action as $value): ?>
                                                <label id="<?=$key?>_<?=$value?>" class="option options_<?=$key?> option_<?=$key?> option_<?=$value?>">
                                                    <input type="checkbox" class="minimal">
                                                    &nbsp;&nbsp;<strong><?=Translate::sprint("grpac_".$value)?></strong>
                                                    &nbsp;&nbsp;
                                                </label>
                                            <?php endforeach; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </table>

                                <div class="form-group">
                                    <label><?=Translate::sprint("Name")?></label>
                                    <input value="<?=$group_access['name']?>" class="form-control" id="name" placeholder="<?=Translate::sprint("Enter name")?>">
                                </div>

                            </div>

                        </form>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button id="cancel" class="btn btn-default"><?=Translate::sprint('Cancel')?></button>
                        <button class="pull-right btn btn-primary" id="add_grp_access"><?=Translate::sprint('Save Changes')?></button>
                    </div>
                </div>

            </div>
        </div>
    </section>

</div>


<?php


$data['actions'] = $actions;
$data['permission'] = $permission;
$data['id'] = $group_access["id"];

$script = $this->load->view("backend/html/grp_access/scripts/edit_group_access_script",$data,TRUE);
TemplateManager::addScript($script);

?>