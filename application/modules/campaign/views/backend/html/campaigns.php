<div class="col-sm-7">
    <div class="box box-solid">
        <div class="box-header">

            <div class="box-title">

                <div class=" row ">
                    <div class="pull-left col-md-12">
                        <b><i class="mdi mdi-history"></i>&nbsp;&nbsp;
                            <?= Translate::sprint("Campaigns", "") ?></b>
                    </div>
                    <!--  DENY ACCESS TO ROLE "GUEST" -->

                </div>
            </div>


        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">


            <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <!--<th>ID</th>-->
                    <th><?= Translate::sprint("Name", "") ?></th>
                    <th><?= Translate::sprint("Type", "") ?></th>

                    <?php if (GroupAccess::isGranted('user', USER_ADMIN)) : ?>
                        <th><?= Translate::sprint("Owner", "") ?></th>
                    <?php endif; ?>
                    <th><?= Translate::sprint("Status", "") ?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <?php if (count($list)) { ?>
                    <?php foreach ($list as $campaign) { ?>

                        <tr>
                            <!--<td><B>#<? /*= $campaign['id'] */ ?></B></td>-->
                            <td>
                                <?= Text::output($campaign['name']) ?>
                            </td>
                            <td>
                                <span class="badge bg-red"><?= Translate::sprint(ucfirst($campaign['type'])) ?></span>
                            </td>

                            <?php if (GroupAccess::isGranted('user', USER_ADMIN)) : ?>
                                <td>
                                    <a style="font-size: 11px"><u><?= ucfirst($this->mUserModel->getUserNameById($campaign['user_id'])) ?></u></a>
                                </td>
                            <?php endif; ?>

                            <td>
                                <?php


                                if ($campaign['status'] == -1) {

                                    echo '<span class="badge bg-blue"><i class="mdi mdi-history"></i> &nbsp;' ?> <?= Translate::sprint("Not Approved") ?><?php echo '&nbsp;&nbsp;</span>';

                                } else if ($campaign["estimation"] > 0 and $campaign["estimation"] == $campaign["received"] and $campaign["estimation"] > $campaign["seen"]) {

                                    echo '<span class="badge bg-blue"  data-toggle="tooltip" title="' . Translate::sprint("All notifications are pushed to closer users") . '"> <i class="mdi mdi-history"></i> &nbsp;' ?> <?= Translate::sprint("Pushed", "") ?><?php echo '&nbsp;&nbsp;</span><br>';

                                } else if ($campaign["estimation"] > 0 and $campaign["estimation"] == $campaign["received"] and $campaign["estimation"] == $campaign["seen"]) {

                                    echo '<span class="badge bg-green"  data-toggle="tooltip" title="' . Translate::sprint("All notifications are seen by closer users") . '"> <i class="mdi mdi-history"></i> &nbsp;' ?> <?= Translate::sprint("Completed", "") ?><?php echo '&nbsp;&nbsp;</span>';

                                } else if ($campaign["estimation"] > 0 and $campaign["received"] == 0 and $campaign["seen"] == 0) {

                                    echo '<span class="badge bg-yellow"  data-toggle="tooltip" title="' . Translate::sprint("The campaign is waiting") . '"> <i class="mdi mdi-history"></i> &nbsp;' ?> <?= Translate::sprint("Pending", "") ?><?php echo '&nbsp;&nbsp;</span>';

                                } else {

                                }


                                if ($campaign['received'] > 0) {
                                    if ($campaign['received'] > 0 && $campaign['received'] < $campaign['estimation']) {
                                        echo '<span data-toggle="tooltip" title="' . $campaign['received'] . "/" . $campaign['estimation'] . " " . Translate::sprint(" are received notifications") . '" class="badge bg-light-blue"> <i class="mdi mdi-inbox-arrow-down"></i>&nbsp;&nbsp;' . $campaign['received'] . "/" . $campaign['estimation'] . '</span><br>';
                                    }
                                }

                                if ($campaign['seen'] > 0) {
                                    if ($campaign['seen'] > 0 && $campaign['seen'] < $campaign['estimation']) {
                                        echo '<span data-toggle="tooltip" title="' . $campaign['seen'] . "/" . $campaign['estimation'] . " " . Translate::sprint(" are seen notifications") . '" class="badge bg-light-blue"> <i class="mdi mdi-eye"></i>&nbsp;&nbsp;' . $campaign['seen'] . "/" . $campaign['estimation'] . '</span><br>';
                                    }
                                }


                                ?>

                            </td>

                            <td align="right">

                                <?php if (GroupAccess::isGranted('user', USER_ADMIN) and $campaign['status'] == -1) { ?>
                                    <a class="text-blue"
                                       href="<?= admin_url("campaign/campaigns?push=" . $campaign['id']) ?>"
                                       data-toggle="tooltip" title="<?= Translate::sprint("Push", "") ?>">
                                        <i class=" fa fa-paper-plane"></i>&nbsp;&nbsp;&nbsp;
                                    </a>
                                    <a href="#" id="delete-<?= $campaign['id'] ?>" data-toggle="tooltip"
                                       title="<?= Translate::sprint("Delete", "") ?>">
                                        <span class="glyphicon glyphicon-trash"></span>&nbsp;&nbsp;
                                    </a>
                                <?php } else { ?>

                                    <?php if ($this->mUserBrowser->getData("id_user") == $campaign['user_id']) { ?>
                                        <a href="<?= site_url('ajax/campaign/archiveCampaign?id=' . $campaign['id']) ?>"
                                           class="linkAccess" onclick="return false;" data-toggle="tooltip"
                                           title="<?= Translate::sprint("Archive", "") ?>">
                                            <span class="glyphicon glyphicon-save"></span>&nbsp;&nbsp;
                                        </a>
                                        <a href="<?= site_url('ajax/campaign/duplicateCampaign?id=' . $campaign['id']) ?>"
                                           title="<?= Translate::sprint("Duplicate", "") ?> " class="linkAccess"
                                           onclick="return false;" data-toggle="tooltip"
                                           title="<?= Translate::sprint("Duplicate") ?>">
                                            <span class="glyphicon glyphicon-duplicate"></span>&nbsp;&nbsp;
                                        </a>
                                    <?php } ?>

                                <?php } ?>



                                <?php if (GroupAccess::isGranted('campaign', DELETE_CAMPAIGNS)): ?>
                                    <script src="<?= base_url("views/skin/backend/plugins/jQuery/jQuery-2.1.4.min.js") ?>"></script>
                                    <script>

                                        $("div #delete-<?=$campaign['id']?>").on('click', function () {
                                            if (!confirm("Are you sur ?") == true) {

                                            } else {
                                                window.location.href = "<?=admin_url('archiveCampaign?id=' . $campaign['id'])?>";
                                            }
                                            return false;
                                        });

                                        $("div #duplicate-<?=$campaign['id']?>").on('click', function () {

                                            if (!confirm("Are you sur to duplicate this campaign ?") == true) {

                                            } else {
                                                window.location.href = "<?=admin_url('duplicateCampaign?id=' . $campaign['id'])?>";
                                            }

                                            return false;
                                        });

                                    </script>
                                <?php endif; ?>

                            </td>


                        </tr>

                    <?php } ?>


                <?php } else { ?>
                    <tr>
                        <td colspan="3">
                            <?= Translate::sprint("No Campaigns", "") ?></td>
                    </tr>
                <?php } ?>


                </tbody>
            </table>

            <div class="row">
                <div class="col-sm-5">
                    <div calass="dataTables_info" id="example2_info" role="status" aria-live="polite">

                    </div>

                </div>
                <div class="col-sm-7">
                    <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">

                        <?php

                        echo $pagination->links(array(
                            "owner" => $this->input->get("owner"),
                            "status" => $this->input->get("status")
                        ), admin_url("campaign/campaigns"));

                        ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>

<?php if (GroupAccess::isGranted('campaign',DELETE_CAMPAIGNS)) : ?>
<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">

                <div class="row">

                    <div style="text-align: center">
                        <h3 class="text-red"><?= Translate::sprint("Are you sure?") ?></h3>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="_apply"
                        class="btn btn-flat btn-primary pull-right"><?= Translate::sprint("Yes") ?></button>
                <button type="button" class="btn btn-flat btn-default pull-right"
                        data-dismiss="modal"><?= Translate::sprint("No") ?></button>
            </div>
        </div>

        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

    <?php

    $script = $this->load->view('backend/html/scripts/campaigns-script',NULL,TRUE);
    TemplateManager::addScript($script);

    ?>


<?php endif; ?>



