<?php


$stores = $data[Tags::RESULT];
$pagination = $data['pagination'];


$category_id = intval($this->input->get("category_id"));

if ($category_id > 0)
    $categoryName = "&nbsp;&nbsp;&nbsp;<span class='badge bg-blue'>&nbsp;" . Translate::sprint(Text::output($this->mStoreModel->getCatName($category_id))) . "&nbsp;&nbsp;<a style='color:#fff !important;' href='" . admin_url("store/stores") . "'>x</a>&nbsp;</span>";
else
    $categoryName = "";


$owner_id = intval($this->input->get("owner_id"));

if ($owner_id > 0)
    $ownerName = "&nbsp;&nbsp;&nbsp;<span class='badge bg-red'>&nbsp;" . ucfirst(Text::output($this->mUserModel->getUserNameById($owner_id))) . "&nbsp;&nbsp;<a style='color:#fff !important;' href='" . admin_url("store/stores") . "'>x</a>&nbsp;</span>";
else
    $ownerName = "";


$status = intval($this->input->get("status"));

if ($status > 0)
    $statusName = "&nbsp;&nbsp;&nbsp;<span class='badge bg-green'>&nbsp;" . Translate::sprint("My stores") . "&nbsp;&nbsp;<a style='color:#fff !important;' href='" . admin_url("store/stores") . "'>x</a>&nbsp;</span>";
else
    $statusName = "";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

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
                <div class="box box-solid">
                    <div class="box-header">

                        <div class="box-title" style="width : 100%;">

                            <div class=" row ">
                                <div class="pull-left col-md-8">
                                    <b><?= Translate::sprint("Stores") ?></b> <?= $categoryName ?><?= $ownerName ?><?= $statusName ?>
                                </div>
                                <div class="pull-right col-md-4">
                                    <?php if ($this->mUserBrowser->getData("typeAuth") != "guest") { ?>
                                        <a href="<?= admin_url("store/create") ?>">
                                            <button type="button" data-toggle="tooltip" title="<?= Translate::sprint("Create new store", "") ?> "
                                                    class="btn btn-primary btn-sm pull-right"><span
                                                    class="glyphicon glyphicon-plus"></span></button>
                                        </a>
                                    <?php } ?>

                                    <form method="get" action="<?= admin_url("store/stores") ?>">

                                        <div class="input-group input-group-sm">
                                            <input class="form-control" size="30" name="search" type="text"
                                                   placeholder="<?= Translate::sprint("Search") ?>"
                                                   value="<?= htmlspecialchars($this->input->get("search")) ?>">
                                            <span class="input-group-btn">
                                                <button type="submit" class="btn btn-primary btn-flat"><i
                                                        class="mdi mdi-magnify"></i></button>
                                        </span>
                                        </div>
                                    </form>

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
                                <th width="5%"><?= Translate::sprint("Image") ?></th>
                                <th width="15%"><?= Translate::sprint("Name") ?></th>
                                <th width="15%"><?= Translate::sprint("AR Name") ?></th>
                                <th width="10%"><?= Translate::sprint("Owner") ?></th>
                                <th width="10%"><?= Translate::sprint("Category") ?></th>
                                <th width="10%"><?= Translate::sprint("Status") ?></th>
                                <th width="10%"><?= Translate::sprint("Rating") ?></th>
                                <th width="10%"><?= Translate::sprint("Reviews") ?> </th>
                                <th width="15%"><?= Translate::sprint("Action") ?></th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php if (!empty($stores)) { ?>

                                <?php foreach ($stores AS $store) { ?>

                                    <?php


                                    $token = $this->mUserBrowser->setToken(Text::encrypt($store['id_store']));

                                    ?>
                                    <tr class="store_<?= $token ?>" role="row" class="odd">

                                        <td>
                                            <?php

                                            try {


                                                if (!is_array($store['images']))
                                                    $images = json_decode($store['images'], JSON_OBJECT_AS_ARRAY);
                                                else
                                                    $images = $store['images'];


                                                if (isset($images[0])) {
                                                    $images = $images[0];
                                                    if (isset($images['100_100']['url'])) {
                                                        echo '<img src="' . $images['100_100']['url'] . '"width="50" height="50" alt="Product Image">';
                                                    } else {
                                                        echo '<img src="' . base_url("views/skin/backend/images/def_logo.png") . '"width="50" height="50" alt="Product Image">';
                                                    }
                                                } else {
                                                    echo '<img src="' . base_url("views/skin/backend/images/def_logo.png") . '"width="50" height="50" alt="Product Image">';
                                                }

                                            } catch (Exception $e) {
                                                $e->getMessage();
                                                echo '<img src="' . base_url("views/skin/backend/images/def_logo.png") . '"width="50" height="50" alt="Product Image">';
                                            }

                                            ?>
                                        </td>

                                        <td>
                                            <span style="font-size: 14px"><?=Text::output($store['name'])?></span>
                                            <?php if($store['featured']==1): ?>
                                                &nbsp;&nbsp;<span class="badge bg-blue-active"  style="font-size: 10px;text-transform: uppercase"><i class="mdi mdi-check"></i>&nbsp;<?=Translate::sprint("Featured")?></span>
                                            <?php endif;?><br>
                                            <i class="mdi mdi-map-marker"></i>&nbsp;&nbsp;
                                            <span style="font-size: 11px"><?=  Text::output($store['address'])?></span>
                                        </td>
                                        <td>
                                            <span style="font-size: 14px"><?=Text::output($store['name_ar'])?></span>
                                            <?php if($store['featured']==1): ?>
                                                &nbsp;&nbsp;<span class="badge bg-blue-active"  style="font-size: 10px;text-transform: uppercase"><i class="mdi mdi-check"></i>&nbsp;<?=Translate::sprint("Featured")?></span>
                                            <?php endif;?><br>
                                            <i class="mdi mdi-map-marker"></i>&nbsp;&nbsp;
                                            <span style="font-size: 11px"><?=  Text::output($store['address'])?></span>
                                        </td>

                                        <td>
                                            <a href="<?= admin_url("store/stores?owner_id=" . $store['user_id']) ?>"><u><?= ucfirst($this->mUserModel->getUserNameById($store['user_id'])) ?></u></a>
                                        </td>
                                        <td>
                                            <a href="<?= admin_url("store/stores?category_id=" . $store['category_id']) ?>"><u><?= Translate::sprint(Text::output($this->mStoreModel->getCatName($store['category_id']))) ?></u></a>
                                        </td>
                                        <td>

                                            <?php

                                            if ($store['status'] == 1) {
                                                echo '<span class="badge bg-green">' . Translate::sprint("Enabled") . '</span>';
                                            } else if ($store['status'] == 0) {
                                                echo '<span class="badge bg-red">' . Translate::sprint("Disabled") . '</span>';
                                            } else if ($store['status'] == -1) {
                                                echo '<span class="badge bg-red">' . Translate::sprint("Blocked") . '</span>';
                                            }

                                            ?>


                                        </td>

                                        <td>
                                            <span style="font-size: 12px"><?php if (!empty($store['votes'])) {
                                                    echo round($store['votes'], 2) . " /5";
                                                } else {
                                                    echo " 0 ";
                                                } ?> </span>

                                        </td>
                                        <td>
                                            <a href="<?= admin_url("store/reviews?id=" . $store['id_store']) ?>">
                                                <button type="button" class="btn btn-primary">
                                                    <?= Translate::sprint("Reviews", "") ?>
                                                </button>
                                            </a>
                                        </td>

                                        <!--  DENY ACCESS TO ROLE "GUEST" -->

                                        <td align="right">

                                            <?php

                                            if ($this->mUserBrowser->getData("typeAuth") == "admin") {
                                                if ($store['status'] == 1) {
                                                    echo ' <a href="' . site_url("ajax/store/statusStore?store=" . $store['id_store']) . '"  class="linkAccess" onclick="return false;"><button type="button" data-toggle="tooltip" title="Enabled" class="btn btn-sm" onclick="return false;"><i style="color:#29910d  !important" class="fa fa-check" aria-hidden="true"></i></button></a>&nbsp;&nbsp;';
                                                } else if ($store['status'] == 0) {
                                                    echo ' <a href="' . site_url("ajax/store/statusStore?store=" . $store['id_store']) . '"  class="linkAccess" onclick="return false;"><button type="button" data-toggle="tooltip" title="Disabled" class="btn btn-sm"  onclick="return false;"><i style="color:#c60f0f !important" class="fa fa-times" aria-hidden="true"></i></button></a>&nbsp;&nbsp; ';
                                                }
                                            }

                                            ?>


                                            <?php if ($store['status'] == 1 || $this->mUserBrowser->getData("typeAuth") == "admin") { ?>

                                            <?php } ?>

                                            <?php if ($store['user_id'] != $this->mUserBrowser->getData("id_user") && $this->mUserBrowser->getData("typeAuth") == "admin") { ?>
                                                <a href="<?= admin_url("store/edit?id=" . $store['id_store']) ?>">
                                                    <button type="button" data-toggle="tooltip" title="Detail" class="btn btn-sm"><i
                                                            class="fa fa-eye"></i></button>
                                                </a>
                                            <?php } else { ?>
                                                <a href="<?= admin_url("store/edit?id=" . $store['id_store']) ?>">
                                                    <button type="button" data-toggle="tooltip" title="Update" class="btn btn-sm"><span
                                                            class="glyphicon glyphicon-edit"></span></button>
                                                </a>
                                            <?php } ?>


                                            <a href="#" data-toggle="modal"
                                               data-target="#modal-default-<?= md5($store['id_store']) ?>">
                                                <button type="button" class="btn btn-sm"><span
                                                        class="glyphicon glyphicon-trash"></span></button>
                                            </a>

                                            <div class="modal fade" id="modal-default-<?= md5($store['id_store']) ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">

                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title"></h4>
                                                        </div>
                                                        <div class="modal-body">

                                                            <div class="row">

                                                                <div style="text-align: center">
                                                                    <p class="text-red"><?= Translate::sprint("Are you sure you want to delete") ?> <?= $store['name'] . " ?" ?></p>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default pull-left"
                                                                    data-dismiss="modal"><?= Translate::sprint("Cancel", "Cancel") ?></button>
                                                            <button type="button" id="_delete"
                                                                    data="<?= ($store['id_store']) ?>"
                                                                    class="btn btn-flat btn-primary"><?= Translate::sprint("Delete", "Delete") ?></button>
                                                        </div>
                                                    </div>

                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>


                                            <?php

                                            //                            if($this->mUserBrowser->getData("typeAuth") =="admin"
                                            //                                && $store->user_id!=$this->mUserBrowser->getData("id_user")  ) {
                                            //                                echo ' <a href="'.admin_url("ajax/statusStore?store=".$store->id_store).'"  class="linkAccess" onclick="return false;"><button type="button" class="btn btn-sm"  onclick="return false;"><i style="color:#c60f0f !important" class="fa fa-ban" aria-hidden="true"></i> &nbsp;&nbsp;Block</button></a> ';
                                            //                            }

                                            ?>
                                        </td>

                                        <!--  -->


                                    </tr>
                                <?php } ?>


                            <?php } else { ?>
                                <tr>
                                    <td colspan="8" align="center">
                                        <div
                                            style="text-align: center"><?= Translate::sprint("No data found", "") ?></div>
                                    </td>
                                </tr>

                            <?php } ?>
                            </tbody>
                            <!-- <tfoot>
                             <tr>
                               <th>Rendering engine</th>
                               <th>Browser</th>
                               <th>Platform(s)</th>
                               <th>Engine version</th>
                               <th>CSS grade</th>
                             </tr>
                             </tfoot>-->
                        </table>

                        <div class="row">
                            <div class="col-sm-5">
                                <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">

                                </div>

                            </div>
                            <div class="col-sm-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">

                                    <?php

                                    echo $pagination->links(array(
                                        "status" => intval($this->input->get("status")),
                                        "search" => $this->input->get("search"),
                                        "category_id" => intval($this->input->get("category_id")),
                                        "owner_id" => intval($this->input->get("owner_id")),
                                    ), admin_url("store/stores"));

                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->


                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<!-- page script -->
<script src="<?= base_url("views/skin/backend/plugins/jQuery/jQuery-2.1.4.min.js") ?>"></script>
<script>
    $(function () {
        $("#example1").DataTable();
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    });


    $('a.linkAccess').on('click', function () {
        var url = ($(this).attr('href'));
        var cat = getURLParameter(url, 'store');


        //calling the ajax function
        pop(cat);
    });


    function getURLParameter(url, name) {
        return (RegExp(name + '=' + '(.+?)(&|$)').exec(url) || [, null])[1];
    }

    function pop(cat) {

        $.ajax({
            type: 'post',
            url: "<?=  site_url("ajax/store/status")?>",
            dataType: 'json',
            data: {'id': cat, 'type': 'store'},
            beforeSend: function (xhr) {
                $(".linkAccess").attr("disabled", true);
            }, error: function (request, status, error) {
                alert(request.responseText);
                $(".linkAccess").attr("disabled", false);
                console.log(request);
            },
            success: function (data, textStatus, jqXHR) {

                $(".linkAccess").attr("disabled", false);
                if (data.success === 1) {
                    document.location.reload();
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
    }

</script>


<script>


    $("div #_delete").on('click', function () {

        var selector = $(this);

        var id = $(this).attr("data");

        $.ajax({
            url: "<?=  site_url("ajax/store/delete")?>",
            data: {"id": id, "type": "store"},
            dataType: 'json',
            type: 'POST',
            beforeSend: function (xhr) {
                selector.attr("disabled", true);
            }, error: function (request, status, error) {
                alert(request.responseText);
                selector.attr("disabled", false);

                console.log(request);
            },
            success: function (data, textStatus, jqXHR) {

                selector.attr("disabled", false);
                if (data.success === 1) {
                    document.location.reload();
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


</script>
